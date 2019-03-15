<?php

namespace CraftKeen\FCRBundle\Repository;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\FCRBundle\Entity\CareersEmployee;

class CareersEmployeeRepository extends TranslatableEntityRepository
{
    /**
     * @param Language $language
     * @param Site $site
     * @param string $status
     * @param int $limit
     *
     * @return array|CareersEmployee[]
     */
    public function findRand(Language $language, Site $site = null, $status = 'live', $limit = 4)
    {
        $qb = $this->createQueryBuilder('p');
        $platform = $this->getEntityManager()->getConnection()->getDatabasePlatform();
        $qb->select('p.id')
            ->addSelect('RAND() as HIDDEN rand')
            ->andWhere($qb->expr()->eq(
                'p.status',
                $platform->quoteStringLiteral($status)
            ))
            ->andWhere($qb->expr()->eq(
                'p.lang',
                $platform->quoteStringLiteral(sprintf('%s', $language->getId()))
            ))
            ->addOrderBy('rand')
            ->setMaxResults($limit);

        if ($site) {
            $qb->andWhere($qb->expr()->eq('p.site', $site->getId()));
        }

        $ids = array_map(function ($item) {
            return $item['id'];
        }, $qb->getQuery()->getArrayResult());

        return $this->findBy(['id' => $ids]);
    }
}
