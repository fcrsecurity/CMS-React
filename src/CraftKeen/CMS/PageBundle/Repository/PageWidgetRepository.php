<?php

namespace CraftKeen\CMS\PageBundle\Repository;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\PageBundle\Entity\Widget;
use Doctrine\ORM\EntityRepository;

/**
 * PageWidgetRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PageWidgetRepository extends EntityRepository
{
    /**
     * @param Language $lang
     *
     * @return array
     */
    public function allPageWidget($lang)
    {
        $query = $this->createQueryBuilder('pw')
            ->leftJoin(Page::class, 'p', 'WITH', 'pw.page = p.id AND p.lang = :lang')
            ->where('p.status = :status')
            ->setParameter(':status', 'live')
            ->setParameter(':lang', $lang)
        ;

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
     * Get all widgets by page
     *
     * @param  [type]
     *
     * @return array
     */
    public function findWidgetsByPage($page = null)
    {
        $query = $this->getEntityManager()->createQueryBuilder()
            ->addSelect('w.id')
            ->addSelect('widget.id as wid')
            ->addSelect('widget.name')
            ->addSelect('w.config')
            ->addSelect('w.data')
            ->addSelect('w.dataType')
            ->addSelect('w.tplArea')
            ->addSelect('widget.macros')
            ->from(PageWidget::class, 'w')
            ->leftJoin(Widget::class, 'widget', 'WITH', 'w.widget = widget.id')
            ->where("w.page = :page")
            ->setParameter('page', $page)
            ->getQuery();

        return $query->getResult();
    }

    /**
     * Delete page
     *
     * @param  [type]
     *
     * @return boolean
     */
    public function deleteByPage($page = null)
    {
        if ($page === null) {
            return false;
        }
        else {
            $query = $this->createQueryBuilder('pw')
                ->delete()
                ->where('pw.page = :page')
                ->setParameter("page", $page)
                ->getQuery()
                ->execute();
            return true;
        }
    }
}