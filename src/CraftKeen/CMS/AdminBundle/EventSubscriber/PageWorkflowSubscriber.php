<?php

namespace CraftKeen\CMS\AdminBundle\EventSubscriber;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Workflow\Event\Event;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use CraftKeen\CMS\AdminBundle\Entity\Inbox;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\UserBundle\Entity\User;

/**
 * TODO: We need to Implement Workflow thought AbstractWorkflow Class to reduce and optimize code
 * Class PageWorkflowSubscriber
 * @package CraftKeen\CMS\AdminBundle\EventSubscriber
 */
class PageWorkflowSubscriber implements EventSubscriberInterface
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
     * WorkflowListener constructor.
     *
     * @param EntityManager $em
     * @param TokenStorage $tokenStorage
     * @param RequestStack $requestStack
     * @param $router
     */
    public function __construct(EntityManager $em, TokenStorage $tokenStorage, RequestStack $requestStack, $router, $container)
    {
        $this->em = $em;
        $this->user = $tokenStorage->getToken()->getUser();
        $this->request = $requestStack->getCurrentRequest();
        $this->router = $router;
        $this->container = $container;
    }

    /**
     * @param Event $event
     */
    public function onTransition(Event $event)
    {
        /** @var Page $currPage */
        $currPage = $page = $event->getSubject();
        $transition = $event->getTransition()->getName();

        /** @var EntityManager $em */
        $em = $this->em;

        switch ($transition) {
            case 'publish':

                $sourcePageId = $page->getCopyOf();
                /** @var Page $sourcePage */
                $sourcePage = $this->em->getRepository(Page::class)->findOneById($sourcePageId);

                /*
                        _||_
                       (   )   \/_
                      ( * * )  /
                       ( - )  /
                       __|___/
                      /\fcr /
                     /  \  /
                     \   \/    ____________
                      \  |    (  _BelDevs_/\
                     /|\ | /  ( /       / ( 0 )
                         |/    \       / ( * * )
                        /\00    \     /   (   )
                       /  \     /    /|\
                    __/    \__ /__
                */

                // Menu
//                $sourceMenuItems = $this->em->getRepository(Menu::class)->findByPage($sourcePageId);
//                $draftMenuItems = $this->em->getRepository(Menu::class)->findByPage($page);
//                $sourceMenuTypes = [];
//                $draftMenuTypes = [];
//
//                if (count($sourceMenuItems) > 0) {
//                    foreach ($sourceMenuItems as $sourceMenuItem) {
//                        $menutype = $sourceMenuItem->getType()->getName();
//                        $sourceMenuTypes[$sourceMenuItem->getId()] = $menutype;
//                        $sourceTypes[$menutype] = $menutype;
//                    }
//
//                    $sourceMenuTypes = array_unique($sourceMenuTypes);
//                }
//
//                if (is_array($draftMenuItems) && count($draftMenuItems) > 0) {
//                    foreach ($draftMenuItems as $draftMenuItem) {
//                        $menutype = $draftMenuItem->getType()->getName();
//                        $draftMenuTypes[$menutype] = $menutype;
//
//                        // If source page has item in type
//                        if (isset($sourceTypes[$draftMenuItem->getType()->getName()])) {
//                            //dump('ignore');
//                        } else {
//                            // add
//                            $menu = new Menu;
//                            $menu->setItemType('page');
//                            $menu->setName($page->getName());
//                            $menu->setPage($sourcePage);
//                            $menu->setType($draftMenuItem->getType());
//                            $menu->setLang($page->getLang());
//                            $menu->setTargetBlank(false);
//                            $menu->setSortOrder(10);
//                            $menu->setCreatedBy($this->user);
//                            $menu->setCreated(new \DateTime());
//                            $menu->setUpdated(new \DateTime());
//                            $em->persist($menu);
//                        }
//                    }
//                    $em->flush();
//                } else {
//                    // if no menu items in draft - delete items from live
//                    foreach ($sourceMenuItems as $item) {
//                        $em->remove($item);
//                    }
//                    $em->flush();
//                }
//
//                foreach ($sourceMenuTypes as $id => $type) {
//                    if (!in_array($type, $draftMenuTypes)) {
//                        $item = $this->em->getRepository(Menu::class)->findOneById($id);
//                        if (!is_null($item)) {
//                            $em->remove($item);
//                        }
//                    }
//                }
//                $em->flush();

                // Menu end

                /*
                        _||_
                       (   )   \/_
                      ( * * )  /
                       ( - )  /
                       __|___/
                      /\fcr /
                     /  \  /
                     \   \/    ____________
                      \  |    (  _BelDevs_/\
                     /|\ | /  ( /       / ( 0 )
                         |/    \       / ( * * )
                        /\00    \     /   (   )
                       /  \     /    /|\
                    __/    \__ /__
                */

                if (is_null($sourcePageId)) {
                    //$page->setIsApproved(true);
                } else {
                    // Copy edited data to source page
                    $sourcePage->setName($page->getName());
                    $sourcePage->setHeroTitle($page->getHeroTitle());
                    $sourcePage->setHero($page->getHero());
                    $sourcePage->setHeroVideo($page->getHeroVideo());
                    $sourcePage->setStatus('live');
                    $sourcePage->setLayout($page->getLayout());
                    $sourcePage->setMetaTitle($page->getMetaTitle());
                    $sourcePage->setMetaDescription($page->getMetaDescription());
                    $sourcePage->setMetaKeywords($page->getMetaKeywords());
                    $sourcePage->setUpdated(new \DateTime());
                    $sourcePage->setVersion($page->getVersion());
                    $sourcePage->setIsIndexed($page->getIsIndexed());
                    $sourcePage->setTemplate($page->getTemplate());
                    $sourcePage->setLang($page->getLang());
                    $sourcePage->setLangParent($page->getLangParent());
                    $sourcePage->setParent($page->getParent());
                    $sourcePage->setUpdatedBy($page->getUpdatedBy());
                    $sourcePage->setVersionComment($page->getVersionComment());
                    //$sourcePage->setIsApproved(true);
                    $sourcePage->setAccess($page->getAccess());
                    $sourcePage->setScripts($page->getScripts());
                    $sourcePage->setSite($page->getSite());

                    $em->persist($sourcePage);

                    // Get all edited widgets for copied page
                    $pageWidgets = $this->em->getRepository(PageWidget::class)->findBy(
                        ['page' => $page]
                    );

                    $result = $this->em->getRepository(PageWidget::class)->deleteByPage($sourcePage);

                    // Copy all edited widgets from copied page to source page
                    /** @var PageWidget $pageWidget */
                    foreach ($pageWidgets as $pageWidget) {
                        $newPageWidget = new PageWidget();
                        $newPageWidget->setPage($sourcePage);
                        $newPageWidget->setWidget($pageWidget->getWidget());
                        $newPageWidget->setConfig($pageWidget->getConfig());
                        $newPageWidget->setData($pageWidget->getData());
                        $newPageWidget->setDataType($pageWidget->getDataType());
                        $newPageWidget->setTplArea($pageWidget->getTplArea());
                        $newPageWidget->setStatus($pageWidget->getStatus());

                        $em->persist($newPageWidget);
                    }

                    // Delete copied page
                    $this->em->getRepository(Page::class)->deleteCopiesOfPage($sourcePage);

                    $currPage = $sourcePage;

                    // Send message to author
                    $author = $sourcePage->getUpdatedBy();
                    $approver = $this->user;

                    $url = $this->router->generate('craftkeen_cms_page_admin_page_show', ['id' => $currPage->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                    // Prepare Message:
                    $message = sprintf(
                        'Your page <a href="%s" target="_blank">"%s"</a> was Published!',
                        $url,
                        $currPage->getName()
                    );

                    $inbox = new Inbox;
                    $inbox->setSender($approver);
                    $inbox->setSubject('Your page was Published!');
                    $inbox->setRecipient($author);
                    $inbox->setMessage($message);
                    $inbox->setIsRead(false);

                    $em->persist($inbox);
                    $this->sendNotification($inbox);
                }
                $em->flush();

                break;

            case 'reject':
                $author = $currPage->getUpdatedBy();
                $approver = $this->user;

                $url = $this->router->generate('craftkeen_cms_page_admin_page_show', ['id' => $currPage->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                $inbox = new Inbox;
                $inbox->setSender($approver);
                $inbox->setSubject('Your page was declined!');
                $inbox->setRecipient($author);
                $inbox->setMessage(sprintf(
                    'Your page <a href="%s" target="_blank">"%s"</a> was declined.',
                    $url,
                    $currPage->getName()
                ));
                $inbox->setIsRead(false);

                $em->persist($inbox);
                $this->sendNotification($inbox);
                break;

            case 'to_review':
                $approvers = $this->getApprovers($currPage);
                $url = $this->router->generate('craftkeen_cms_page_admin_page_show', ['id' => $currPage->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

                foreach ($approvers as $approver) {
                    $message = sprintf(
                        'Click on <a href="%s" target="_blank">"%s"</a> to see revision to approve.',
                        $url,
                        $currPage->getName()
                    );
                    if ($currPage->getVersionComment()) {
                        $message .= sprintf(
                            '<br /><b>Version Comment:</b><p>%s</p>',
                            $currPage->getVersionComment()
                        );
                    }
                    $inbox = new Inbox;
                    $inbox->setSender($currPage->getUpdatedBy()->getUsername());
                    $inbox->setSubject('Page changes require approval');
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
            'workflow.page_publishing.transition' => 'onTransition',
        ];
    }

    /**
     * Getting list of Users Approvers for provided $object
     *
     * @param Object $object
     * @return array
     */
    private function getApprovers( $object ) {
        $access = unserialize($object->getAccess());

        // Send messages to all approvers or only granted approvers
        $allApprovers = $this->em->getRepository(User::class)->findUserByRole('ROLE_APPROVER');

        if (isset($access['APPROVE']) && is_array($access['APPROVE'])) {
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
        /** @var \Swift_Message $message */
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
