<?php

namespace CraftKeen\CMS\PageBundle\Repository;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\FCRBundle\Repository\TranslatableEntityRepository;
use CraftKeen\CMS\PageBundle\Entity\Page;

/**
 * PageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageRepository extends TranslatableEntityRepository
{
    /**
     * @param null|Page $parent
     * @param null|string $filterSite
     * @param null|Language $lang
     *
     * @return array
     */
    public function allPages($parent = null, $filterSite = null, $lang = null)
    {
        $query = $this->createQueryBuilder('p')->where('p.status = :status')->setParameter('status', 'live');

        if (null != $parent) {
            $query->andwhere('p.parent = :parent')->setParameter('parent', $parent);
        }
        if (null != $filterSite) {
            $query->andWhere('p.site = :site')->setParameter('site', $filterSite);
        }

        if (null != $lang) {
            $query->andWhere('p.lang = :lang')->setParameter('lang', $lang);
        }

        return $query->getQuery()->getResult();
    }

    /**
     * Get All Available Pages to be parent for current page.
     *
     * @param  [type]
     *
     * @return Page[]
     */
    public function findAvailableParents($pageId = null)
    {
        if (null == $pageId) {
            return $this->allPages();
        }
        $page = $this->find($pageId);

        if ($page) {
            $query = $this->createQueryBuilder('p')
                ->where('p.id != :pageId')
                ->andWhere('p.status = live')
                ->setParameter('pageId', $pageId)
                ->getQuery();

            return $query->getResult();
        }
    }

    /**
     * Get copy of page.
     *
     * @param  [type]
     *
     * @return Page[]
     */
    public function findCopy(Page $page)
    {
        $query = $this->createQueryBuilder('p')
            ->where('p.copyOf = :pageId')
            ->setParameter('pageId', $page->getId())
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

    /**
     * @param Page $parent
     * @param Language $language
     *
     * @return null|Page|object
     */
    public function findTranslation(Page $parent, Language $language)
    {
        return $this->findOneBy(['langParent' => $parent, 'lang' => $language]);
    }

    /**
     * Delete all copies of page
     *
     * @param Page $page
     *
     * @return null|bool
     */
    public function deleteCopiesOfPage(Page $page)
    {
        $query = $this->createQueryBuilder('p');
        return $query->delete()
            ->where('p.copyOf = :pageId')
            ->setParameter("pageId", $page->getId())
            ->getQuery()
            ->execute();
    }

    /**
     * Find Current Version of the Page.
     *
     * @param Page $page
     *
     * @return Page
     */
    public function findCurrentVersion(Page $page) {
        $page = $this->findOneById($page->getId());
        if ( 'live' == $page->getStatus()) {
            return $page;
        }
    }

}