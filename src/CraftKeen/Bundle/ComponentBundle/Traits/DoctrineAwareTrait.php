<?php

namespace CraftKeen\Bundle\ComponentBundle\Traits;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;

trait DoctrineAwareTrait
{
    /** @var ManagerRegistry */
    protected $registry;

    /**
     * {@inheritdoc}
     */
    public function setRegistry(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @param $class
     *
     * @return ObjectRepository|EntityRepository
     */
    public function getRepository($class)
    {
        return $this->registry->getManagerForClass($class)->getRepository($class);
    }
}
