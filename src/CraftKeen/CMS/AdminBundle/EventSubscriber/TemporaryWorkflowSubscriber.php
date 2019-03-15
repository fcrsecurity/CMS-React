<?php

namespace CraftKeen\CMS\AdminBundle\EventSubscriber;

use CraftKeen\Bundle\NotificationBundle\Manager\EmailManager;
use CraftKeen\FCRBundle\Entity\BaseEntity;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use CraftKeen\CMS\UserBundle\Entity\User;

/**
 * TODO: We need to Implement Workflow thought AbstractWorkflow Class to reduce and optimize code
 * Class TemporaryWorkflowSubscriber
 * @package CraftKeen\CMS\AdminBundle\EventSubscriber
 */
class TemporaryWorkflowSubscriber implements EventSubscriberInterface
{
    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var User
     */
    private $user;

    /**
     * @var RequestStack
     */
    protected $request;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var EmailManager
     */
    protected $emailManager;

    /**
     * WorkflowListener constructor.
     *
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     * @param RequestStack $requestStack
     * @param Router $router
     * @param EmailManager $emailManager
     *
     * @internal param ContainerInterface $container
     */
    public function __construct(
        EntityManager $em,
        TokenStorage $tokenStorage,
        RequestStack $requestStack,
        $router,
        EmailManager $emailManager
    ) {
        $this->em = $em;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
        $this->emailManager = $emailManager;
    }

    /**
     * @param Event $event
     */
    public function onTransition(Event $event)
    {
        /** @var BaseEntity|Object $object */
        $object = $event->getSubject();
        $transition = $event->getTransition()->getName();

        /** @var EntityManager $em */
        $em = $this->em;

        $url = $this->router->generate('craftkeen_cms_admin_inbox_index');
        if (null !== $this->router->getRouteCollection()->get($object->getEntityBaseRoute() . 'show')) {
            $url = $this->router->generate(
                $object->getEntityBaseRoute() . 'show',
                ['id' => $object->getId()],
                UrlGeneratorInterface::ABSOLUTE_URL
            );
        }
        switch ($transition) {
            case 'publish':
                /** @var BaseEntity $sourceObject */
                $sourceObject = $object->getCopyOf();

                if (is_null($sourceObject)) {
                    // save data for current object
                } else {
                    $refObject = new \ReflectionObject($object);
                    $copy = clone $object;

                    $exception = [
                        'Id',
                        'CopyOf',
                        'Created',
                        'Updated',
                        'UpdatedBy',
                        'CreatedBy',
                        'Status',
                        'Lang',
                        'LangParent',
                        'Category',
                    ];

                    //For Set/Get methods in entity
                    $listMethods = array();
                    foreach ($refObject->getMethods() as $method) {
                        if (substr($method->name, 0, 3) == 'set') {
                            $len = strlen($method->name) - 3;
                            $listMethods[] = substr($method->name, 3, $len);
                        }
                    }

                    foreach ($listMethods as $i => $method) {
                        if (!in_array($method, $exception)) {
                            $setter = 'set' . ucfirst($method);
                            $getter = 'get' . ucfirst($method);

                            $sourceObject->$setter($copy->$getter());
                        }
                    }

                    //For ADD methods in entity
                    $listMethods = array();
                    foreach ($refObject->getMethods() as $method) {
                        if (substr($method->name, 0, 3) == 'add') {
                            $len = strlen($method->name) - 3;
                            $listMethods[] = substr($method->name, 3, $len);
                        }
                    }

                    foreach ($listMethods as $i => $method) {
                        if (!in_array($method, $exception)) {
                            $adder = 'add' . ucfirst($method);
                            $getter = 'get' . ucfirst($method);
                            $remover = 'remove' . ucfirst($method);

                            foreach ($sourceObject->$getter() as $item) {
                                $sourceObject->$remover($item);
                            }

                            foreach ($copy->$getter() as $item) {
                                $sourceObject->$adder($item);
                            }
                        }
                    }

                    $sourceObject->setUpdatedBy($copy->getCreatedBy());
                    $sourceObject->setUpdated(new \DateTime());
                    $em->persist($sourceObject);
                    $em->flush();

                    ### Start - Prepare Event Linstener to avoid soft-deleteable filter for draft records,
                    # as we need really delete them.
                    // initiate an array for the removed listeners
                    $originalEventListeners = array();
                    // cycle through all registered event listeners
                    foreach ($em->getEventManager()->getListeners() as $eventName => $listeners) {
                        foreach ($listeners as $listener) {
                            if ($listener instanceof SoftDeleteableListener) {
                                // store the event listener, that gets removed
                                $originalEventListeners[$eventName] = $listener;

                                // remove the SoftDeletableSubscriber event listener
                                $em->getEventManager()->removeEventListener($eventName, $listener);
                            }
                        }
                    }
                    $em->remove($object);
                    $em->flush();

                    // re-add the removed listener back to the event-manager
                    foreach ($originalEventListeners as $eventName => $listener) {
                        $em->getEventManager()->addEventListener($eventName, $listener);
                    }
                    ### Stop

                    $author = $object->getUpdatedBy();
                    $approver = $this->user;
                    $reflect = new \ReflectionClass($object);
                    $entityClass = $reflect->getShortName();

                    if (null !== $this->router->getRouteCollection()->get($object->getEntityBaseRoute() . 'show')) {
                        $url = $this->router->generate(
                            $object->getEntityBaseRoute() . 'show',
                            ['id' => $sourceObject->getId()],
                            UrlGeneratorInterface::ABSOLUTE_URL
                        );
                    }
                    // Prepare Message:
                    $message = sprintf(
                        'Your change <b>"%s"</b> with ID: <b>"%s"</b> was published Live. <a href="%s">View</a>',
                        $this->camelCaseToHuman($entityClass),
                        $sourceObject->getId(),
                        $url
                    );

                    $inbox = new Inbox;
                    $inbox->setSender($approver);
                    $inbox->setSubject('Your Change was Published Live!');
                    $inbox->setRecipient($author);
                    $inbox->setMessage($message);
                    $inbox->setIsRead(false);

                    $em->persist($inbox);
                    $this->sendNotification($inbox);
                }
                break;

            case 'reject':
                $reflect = new \ReflectionClass($object);
                $entityClass = $reflect->getShortName();

                $author = $object->getUpdatedBy();
                if (null == $author) {
                    $author = $object->getCreatedBy();
                }

                // Generate Inbox Subject
                $subject = sprintf(
                    'Changes has been declined for: "%s" / ID "%s"',
                    $this->camelCaseToHuman($entityClass),
                    $object->getId()
                );

                $inbox = new Inbox;
                $inbox->setSender($this->user);
                $inbox->setSubject($subject);
                $inbox->setRecipient($author);
                $inbox->setMessage(sprintf(
                    'Your change was declined for <b>"%s"</b> with ID: <b>"%s"</b> <a href="%s">View</a>',
                    $this->camelCaseToHuman($entityClass),
                    $object->getId(),
                    $url
                ));
                $inbox->setIsRead(false);

                $em->persist($inbox);
                $this->sendNotification($inbox);
                break;

            case 'to_review':
                $approvers = $this->getApprovers($object);
                $reflect = new \ReflectionClass($object);
                $entityClass = $reflect->getShortName();

                foreach ($approvers as $approver) {
                    $message = sprintf(
                        '<b>"%s"</b> ID: <b>"%s"</b> require approval.<br /><a href="%s">View Request</a>',
                        $this->camelCaseToHuman($entityClass),
                        $object->getId(),
                        $url
                    );
                    if ($object->getVersionComment()) {
                        $message .= '<br /><b>Version Comment:</b><br />' . strip_tags($object->getVersionComment());
                    }
                    /** @var User $sender */
                    $sender = $object->getUpdatedBy();
                    if (null == $sender) {
                        $sender = $object->getCreatedBy();
                    }

                    // Generate Inbox Subject
                    $subject = sprintf(
                        'Website Approval Request: "%s" / ID "%s"',
                        $this->camelCaseToHuman($entityClass),
                        $object->getId()
                    );

                    $inbox = new Inbox;
                    $inbox->setSender($sender->getUsername());
                    $inbox->setSubject($subject);
                    $inbox->setRecipient($approver);
                    $inbox->setMessage($message);
                    $inbox->setIsRead(false);

                    $em->persist($inbox);
                    $this->sendNotification($inbox);
                }
                break;
        }
        $em->flush();
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.temporary_publishing.transition' => 'onTransition',
        ];
    }

    /**
     * Getting list for Users for provided $object
     *
     * @param Object $object
     *
     * @return array
     */
    private function getApprovers($object)
    {
        $access = unserialize($object->getAccess());

        // Send messages to all approvers or only granted approvers
        $allApprovers = $this->em->getRepository(User::class)->findUserByRole('ROLE_APPROVER');
        $approvers = $allApprovers;
        if (isset($access['APPROVE']) && is_array($access['APPROVE'])) {
            /** Clean-up Default approvers */
            $approvers = [];
            foreach ($allApprovers as $approver) {
                $userRoles = $approver->getRoles();
                foreach ($access['APPROVE'] as $approveRole) {
                    if (in_array($approveRole, $userRoles)) {
                        $approvers[] = $approver;
                    }
                }
            }
        }

        return $approvers;
    }

    /**
     * Sends Notifications for users
     *
     * @param Inbox $inbox
     *
     * @return int
     */
    protected function sendNotification(Inbox $inbox)
    {
        return $this->emailManager->sendEmail($inbox, 'email/inbox_email.html.twig', [
            'inbox' => $inbox,
        ]);
    }

    /**
     * @param $input
     *
     * @return string
     */
    public function camelCaseToHuman($input)
    {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        foreach ($ret as &$match) {
            $match = $match == strtoupper($match) ? strtolower($match) : ucwords($match);
        }

        return implode(' ', $ret);
    }
}
