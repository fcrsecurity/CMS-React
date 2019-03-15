<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 05.12.17
 * Time: 12:34
 */

namespace CraftKeen\BrochureBuilderBundle\EventSubscriber;

use CraftKeen\BrochureBuilderBundle\Entity\Brochure;
use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Workflow\Event\Event;

class BrochureWorkflowSubscriber implements EventSubscriberInterface
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
     * @var $router
     */
    protected $router;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * BrochureWorkflowSubscriber constructor.
     *
     * @param ContainerInterface $container
     * @param Registry $doctrine
     */
    public function __construct(ContainerInterface $container, Registry $doctrine)
    {
        $this->em = $container->get('doctrine.orm.entity_manager');
        $this->user = $container->get('security.token_storage')->getToken()->getUser();
        $this->router = $container->get('router');
        $this->container = $container;
        $this->doctrine = $doctrine;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            'workflow.brochure_publishing.transition' => 'onTransition',
        ];
    }

    /**
     * @param Event $event
     */
    public function onTransition(Event $event)
    {
        /** @var Brochure $object */
        $object = $event->getSubject();
        $transition = $event->getTransition()->getName();

        switch ($transition) {

            case Brochure::TRANSITION_REJECT:
                $author = $object->getUpdatedBy();
                if (null == $author) {
                    $author = $object->getCreatedBy();
                }
                $approver = $this->user;

                $url = $this->router->generate('brochure_edit', ['id' => $object->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                $inbox = new Inbox;
                $inbox->setSender($approver);
                $inbox->setSubject('Your property was declined!');
                $inbox->setRecipient($author);
                $message = sprintf(
                    'Your brochure <a href="%s" target="_blank">"%s"</a> was declined.',
                    $url,
                    $object->getName()
                );
                if ($object->getVersionComment() && strlen($object->getVersionComment()) > 0) {
                    $message .= sprintf(
                        '<br /><b>Version Comment:</b><p>%s</p>',
                        $object->getVersionComment()
                    );
                }
                if ($object->getRejectComment() && strlen($object->getRejectComment()) > 0) {
                    $message .= sprintf(
                        '<br /><b>Brochure rejected with comment:</b><p>%s</p>',
                        $object->getRejectComment()
                    );
                }
                $inbox->setMessage($message);
                $inbox->setIsRead(false);

                $this->em->persist($inbox);
                $this->sendNotification($inbox);
                $this->em->flush($inbox);
                break;

            case Brochure::TRANSITION_REVIEW:
                $approvers = $this->getApprovers($object);
                $url = $this->router->generate('brochure_view', ['id' => $object->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                foreach ($approvers as $approver) {
                    $message = sprintf(
                        'Click on <a href="%s" target="_blank">"%s"</a> to see revision to approve.',
                        $url,
                        $object->getName()
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
                    $inbox->setSubject('Brochure changes require approval');
                    $inbox->setRecipient($approver);
                    $inbox->setMessage($message);
                    $inbox->setIsRead(false);

                    $this->em->persist($inbox);
                    $this->sendNotification($inbox);
                }
                $this->em->flush();
                break;
            case 'publish':
//                $sourceBrochure = $object->getCopyOf();
//
//                if (is_null($sourceBrochure)) {
//                    $this->em->flush();
//                } else {
//                    // Copy edited data to source brochure
//                    $sourceBrochure = $this->doctrine->getRepository(Brochure::class)->findOneBy(['id' => $sourceBrochure]);
////                    $sourceBrochure->setData($object->getData());
//                    $sourceBrochure->setStatus('live');
//
//                    $this->em->persist($sourceBrochure);
//                    $this->em->flush($sourceBrochure);
//
//                    // Delete copied brochure
//                    $this->em->remove($object);
//                    $this->em->flush($object);
//
//                    $id = $sourceBrochure->getId();
//                }
                break;
            case 'retract':
//                $sourceBrochure = $object->getCopyOf();
//
//                if (is_null($sourceBrochure)) {
//                    $this->em->flush();
//                } else {
//                    // Copy edited data to source brochure
//                    $sourceBrochure = $this->doctrine->getRepository(Brochure::class)->findOneBy(['id' => $sourceBrochure]);
//                    $sourceBrochure->setStatus('live');
//
//                    $this->em->persist($sourceBrochure);
//                    $this->em->flush($sourceBrochure);
//
//                    // Delete copied brochure
//                    $this->em->remove($object);
//                    $this->em->flush($object);
//
//                    $id = $sourceBrochure->getId();
//                }
                break;
            case 'delete':
                $object->setDeletedAt(new \DateTime());
                break;
        }

        $object->setUpdated(new \DateTime());
        $this->em->persist($object);
        $this->em->flush();

    }

    /**
     * Getting list of Users Approvers for provided $object
     *
     * @param Brochure $object
     * @return array
     */
    private function getApprovers(Brochure $object) {
        // Send messages to all approvers
        $approvers = $this->em->getRepository(User::class)->findUserByRole(USER::ROLE_BROCHURE_APPROVER);
        $admins = $this->em->getRepository(User::class)->findUserByRole(USER::ROLE_ADMINISTRATOR);
        $brochureAdmins = $this->em->getRepository(User::class)->findUserByRole(USER::ROLE_BROCHURE_ADMINISTRATOR);
        $superAdmins = $this->em->getRepository(User::class)->findUserByRole(USER::ROLE_SUPERADMINISTRATOR);
        $allApprovers = array_unique(array_merge($approvers, $admins, $brochureAdmins, $superAdmins));
        return $allApprovers;
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

}
