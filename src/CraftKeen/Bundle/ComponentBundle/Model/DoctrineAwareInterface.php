<?php

namespace CraftKeen\Bundle\ComponentBundle\Model;

use Doctrine\Common\Persistence\ManagerRegistry;

interface DoctrineAwareInterface
{
    /**
     * @param ManagerRegistry $registry
     */
    public function setRegistry(ManagerRegistry $registry);
}
