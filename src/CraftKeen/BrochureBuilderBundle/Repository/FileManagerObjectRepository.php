<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 21.11.17
 * Time: 9:50
 */

namespace CraftKeen\BrochureBuilderBundle\Repository;

use CraftKeen\FCRBundle\Repository\TranslatableEntityRepository;

class FileManagerObjectRepository extends TranslatableEntityRepository
{
    /**
     * Find files by meta
     *
     * @param $meta
     * @return array
     */
    public function searchByMeta($meta) {
        return $this->createQueryBuilder('f')
            ->where('f.metaData LIKE :meta')
            ->setParameter('meta', '%' . $meta . '%')
            ->getQuery()
            ->getResult();
    }
}
