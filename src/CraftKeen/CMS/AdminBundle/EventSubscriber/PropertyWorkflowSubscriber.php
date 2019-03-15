<?php

namespace CraftKeen\CMS\AdminBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Doctrine\ORM\EntityManager;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\CMS\AdminBundle\Entity\Inbox;

class PropertyWorkflowSubscriber implements EventSubscriberInterface
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
     * @var $router
     */
    protected $router;

    /**
     * @var $container
     */
    protected $container;


    /**
     * PropertyWorkflowSubscriber constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->user = $container->get('security.token_storage')->getToken()->getUser();
        $this->request = $container->get('request_stack')->getCurrentRequest();
        $this->router = $container->get('router');
        $this->container = $container;
    }

    /**
     * @param Event $event
     */
    public function onTransition(Event $event)
    {
        /** @var Property $object */
        $object = $event->getSubject();
        $transition = $event->getTransition()->getName();

        /** @var EntityManager $em */
        $em = $this->em;

        switch ($transition) {
            case 'publish':
                $sourceObject = $object->getCopyOf();

                if (!is_null($sourceObject)) {
                    $sourceObject->copyDataFrom($object);

                    $sourceObject->setUpdatedBy($object->getUpdatedBy());
                    $sourceObject->setUpdated(new \DateTime());

                    // Update PropertyDetail in copy
                    $detailsSource = $object->getDetails();
                    $details = $sourceObject->getDetails();

                    $details->copyDataFrom($detailsSource);
                    $em->flush($details);

                    // Update PropertyDemographic in copy
                    $demographicSource = $object->getDemographic();
                    $demographic = $sourceObject->getDemographic();

                    $demographic->copyDataFrom($demographicSource);
                    $em->flush($demographic);

                    // Update PropertyFilters in copy
                    $filtersSource = $object->getFilters();
                    $filters = $sourceObject->getFilters();

                    $filters->copyDataFrom($filtersSource);
                    $em->flush($filters);

                    $em->flush($sourceObject);

                    $this->applyFieldChangeForRelatedTranslations($sourceObject);

                    $this->updatePropertySorting();

                    ### Start - Prepate Event Linstened to avoid soft-deleteable filter for draft records, as we need really delete them.
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

                    $em->getRepository(Property::class)->deleteCopiesOfProperty($sourceObject);

                    // re-add the removed listener back to the event-manager
                    foreach ($originalEventListeners as $eventName => $listener) {
                        $em->getEventManager()->addEventListener($eventName, $listener);
                    }
                    ### Stop
                }
                break;
            case 'reject':
                $author = $object->getUpdatedBy();
                if (null == $author) {
                    $author = $object->getCreatedBy();
                }
                $approver = $this->user;

                $url = $this->router->generate('craftkeen_fcr_property_edit_wizard', ['id' => $object->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                $inbox = new Inbox;
                $inbox->setSender($approver);
                $inbox->setSubject('Your property was declined!');
                $inbox->setRecipient($author);
                $message = sprintf(
                    'Your property <a href="%s" target="_blank">"%s"</a> was declined.',
                    $url,
                    $object->getParentName()
                );
                if ($object->getVersionComment()) {
                    $message .= sprintf(
                        '<br /><b>Version Comment:</b><p>%s</p>',
                        $object->getVersionComment()
                    );
                }
                $inbox->setMessage($message);
                $inbox->setIsRead(false);

                $em->persist($inbox);
                $this->sendNotification($inbox);
                $em->flush($inbox);
                break;

            case 'to_review':
                $approvers = $this->getApprovers($object);

                $url = $this->router->generate('craftkeen_fcr_property_edit_wizard', ['id' => $object->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                foreach ($approvers as $approver) {
                    $message = sprintf(
                        'Click on <a href="%s" target="_blank">"%s"</a> to see revision to approve.',
                        $url,
                        $object->getParentName()
                    );
                    if ($object->getVersionComment()) {
                        $message .= sprintf(
                            '<br /><b>Version Comment:</b><p>%s</p>',
                            $object->getVersionComment()
                        );
                    }
                    $sender = $object->getUpdatedBy();
                    if (null == $sender) {
                        $sender = $object->getCreatedBy();
                    }
                    $inbox = new Inbox;
                    $inbox->setSender($sender->getUsername());
                    $inbox->setSubject('Property changes require approval');
                    $inbox->setRecipient($approver);
                    $inbox->setMessage($message);
                    $inbox->setIsRead(false);

                    $em->persist($inbox);
                    $this->sendNotification($inbox);
                }
                $em->flush();
                break;
        }
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.property_publishing.transition' => 'onTransition',
        ];
    }

    /**
     * Getting list of Users Approvers for provided $object
     *
     * @param Property $object
     * @return array
     */
    private function getApprovers( $object ) {
        $access = unserialize($object->getAccess());

        // Send messages to all approvers or only granted approvers
        $allApprovers = $this->em->getRepository(User::class)->findUserByRole('ROLE_APPROVER');
        $approvers = [];
        if (isset($access['APPROVE']) && is_array($access['APPROVE'])) {
            /** @var User $approver */
            foreach ($allApprovers as $approver) {
                $userRoles = $approver->getRoles();
                foreach ($access['APPROVE'] as $approveRole) {
                    if (in_array($approveRole, $userRoles)) {
                        $approvers[] = $approver;
                    }
                }
            }
        } else {
            $approvers = $allApprovers;
        }
        return $approvers;
    }

    /**
     * Send Notifications to Approvers
     * @param Inbox $inbox
     */
    protected function sendNotification(Inbox $inbox ) {
        $message = \Swift_Message::newInstance()
            ->setSubject('New Inbox Message. '.$inbox->getSubject() )
            ->setFrom($this->container->getParameter('fcr_property_cta_email_from'), $this->container->getParameter('fcr_property_cta_email_label'))
            ->setTo($inbox->getRecipient()->getEmail())
            ->setReplyTo($inbox->getRecipient()->getEmail())
            ->setBody(
                $this->container->get('templating')->render('email/inbox_email.html.twig', [
                    'inbox' => $inbox,
                ]), 'text/html'
            );
        $this->container->get('mailer')->send($message);
    }

    /**
     * Apply Mandatory Fields changes for Property Related translations.
     *
     * @param Property $property
     */
    private function applyFieldChangeForRelatedTranslations(Property $property)
    {
        $relatedTranslation = $this->container->get('doctrine')->getRepository(Property::class)
            ->findBy(['langParent' => $property->getId()]);

        if ( null !== $relatedTranslation ) {

            /** @var Property $related */
            foreach ($relatedTranslation as $related) {
                $related->setIsHidden($property->getIsHidden());
                $this->em->flush($related);
            }
        }
    }

    /**
     * Re-calculate property Sorting.
     */
    private function updatePropertySorting()
    {
        $properties = $this->container->get('doctrine')->getRepository(Property::class)
            ->findBy([], ['parentName' => 'ASC']);

        $sortOrder = 0;
        /** @var Property $property */
        foreach ($properties as $property) {
            $property->setSortOrder($sortOrder);
            $sortOrder++;
            $this->em->flush($property);
        }
    }
}
