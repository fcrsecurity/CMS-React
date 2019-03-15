<?php

namespace CraftKeen\CMS\PageBundle\Service;

use Doctrine\ORM\EntityManager;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;

class PageEditor
{

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Constructor
     *
     * @param $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Updates Page Layout
     *
     * @param array $pageData
     *
     * @return bool
     */
    public function updateLayout($pageData)
    {
        $page = $this->em->getRepository(Page::class)->find($pageData['id']);
        $user = $this->em->getRepository(User::class)->find($pageData['author']);

        if ($page && $user) {
            $page->setLayout($pageData['layout']);
            $page->setUpdatedBy($user);
            $page->setVersionComment($pageData['versionComment']);
            $page->setUpdated(new \DateTime);

            // Approve the content change automatically for Administrators
            if (in_array('ROLE_ADMINISTRATOR', $user->getRoles())) {
                //$page->setIsApproved(true);
                $page->setStatus('live');
            }

            $this->em->flush();
            return true;
        }

        return false;
    }

    /**
     * Updates Page Widget Content
     *
     * @param array $pageData
     *
     * @return bool
     */
    public function updatePageWidgetContent($pageData)
    {
        $page = $this->em->getRepository(Page::class)->find($pageData['id']);
        $user = $this->em->getRepository(User::class)->find($pageData['author']);

        if (count($pageData['widgets']) > 0) {
            $saveCount = 0;
            foreach ($pageData['widgets'] as $pw) {
                $pageWidget = $this->em->getRepository(PageWidget::class)->find(key($pw));

                if ($page && $user && $pageWidget) {
                    if (isset($pw[key($pw)]['data'])) {
                        $pageWidget->setData(serialize($pw[key($pw)]['data']));
                    }

                    if (isset($pw[key($pw)]['config'])) {
                        $pageWidget->setConfig(serialize($pw[key($pw)]['config']));
                    }

                    $this->em->flush();

                    $page->setUpdatedBy($user);
                    $page->setVersionComment($pageData['versionComment']);
                    $page->setUpdated(new \DateTime());

                    $this->em->flush();
                    $saveCount++;
                }
            }
            if ($saveCount > 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Create Page Copy With Widgets or return existing
     *
     * @param Page $page
     *
     * @return Page
     */
    public function createPageCopy(Page $page, $user, $setCopyOf = true)
    {
        /** @var EntityManager $em */
        $em = $this->em;

        // Delete item with copyOf to this page and status live
        $query = $em->getRepository(Page::class)->createQueryBuilder('p');
        $query->delete()
            ->where('p.copyOf = :pageId')
            ->andWhere('p.status = :status')
            ->setParameter("pageId", $page->getId())
            ->setParameter('status', 'live')
            ->getQuery()
            ->execute();

        $copy = $em->getRepository(Page::class)->findOneByCopyOf($page->getId());

        if (is_null($copy)) {
            $version = (int)$page->getVersion() + 1;

            $currentDraft = clone $page;
            $currentDraft->setStatus('draft');
            if ($setCopyOf) {
                $currentDraft->setCopyOf($page);
            }
            $currentDraft->setUpdatedBy($user);
            $currentDraft->setVersion($version);
            $em->persist($currentDraft);
            $em->flush($currentDraft);

            $pageWidgets = $this->em->getRepository(PageWidget::class)->findBy(
                ['page' => $page]
            );

            foreach ($pageWidgets as $pageWidget) {
                $newPageWidget = new PageWidget();
                $newPageWidget->setPage($currentDraft);
                $newPageWidget->setWidget($pageWidget->getWidget());
                $newPageWidget->setConfig($pageWidget->getConfig());
                $newPageWidget->setData($pageWidget->getData());
                $newPageWidget->setDataType($pageWidget->getDataType());
                $newPageWidget->setTplArea($pageWidget->getTplArea());
                $newPageWidget->setStatus($pageWidget->getStatus());

                $em->persist($newPageWidget);
                $em->flush($newPageWidget);
            }
            return $currentDraft;
        } else {
            return $copy;
        }
    }
}
