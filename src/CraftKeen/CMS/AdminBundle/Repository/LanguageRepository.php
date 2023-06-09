<?php

namespace CraftKeen\CMS\AdminBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * LanguageRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 *
 * @deprecated Should be moved to CraftKeenTranslationBundle
 */
class LanguageRepository extends EntityRepository
{
    /**
     * @param array $localeToExclude
     *
     * @return array
     */
    public function findAllBut($localeToExclude = [])
    {
        $qb = $this->createQueryBuilder('l');

        return $qb->where('l.locale NOT IN (:localeToExclude)')
            ->setParameter('localeToExclude', $localeToExclude)
            ->getQuery()
            ->getResult();
    }
}
