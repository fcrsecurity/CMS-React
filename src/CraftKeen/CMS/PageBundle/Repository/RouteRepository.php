<?php

namespace CraftKeen\CMS\PageBundle\Repository;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\Route;
use Doctrine\ORM\EntityRepository;

/**
 * RouteRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RouteRepository extends EntityRepository
{
    /**
     * Get route by slug and current page
     *
     * @param  [type]
     *
     * @return Route[]
     */
    public function findBySlugAndSite($slug = null, $site = null)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.page', 'p')
            ->where('p.site=:site')
            ->andWhere('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->setParameter('site', $site)
            ->setMaxResults(1)
            ->getQuery();

        return $query->getOneOrNullResult();
    }

     /**
     * Get route by slug and domain
     *
     * @param  [type]
     *
     * @return Route
     */
    public function findBySlugAndDomain($slug = null, $domain = null)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.page', 'p')
            ->leftJoin('p.site', 's')
            ->where('r.slug = :slug')
            ->setParameter('slug', $slug)
            ->andWhere('s.domain = :domain')
            ->setParameter('domain', $domain)
            ->getQuery();
        return $query->getOneOrNullResult();
    }

    /**
     * Find Route by Page name
     *
     * @param $pageName
     * @return mixed
     */
    public function findSlugByPageName($pageName)
    {
        $query = $this->createQueryBuilder('r')
            ->leftJoin('r.page', 'p')
            ->where('p.name = :pageName')
            ->setParameter('pageName', $pageName)
            ->getQuery();
        $result = $query->getResult();

        /** @var Route $route */
        if (count($result) > 0) {
            foreach ($result as $route) {
                if (null !== $route) {
                    return $route->getSlug();
                }
            }
        }
        return '/';
    }

}