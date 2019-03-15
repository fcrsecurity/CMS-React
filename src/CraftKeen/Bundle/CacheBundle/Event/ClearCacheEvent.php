<?php

namespace CraftKeen\Bundle\CacheBundle\Event;

use Symfony\Component\EventDispatcher\Event;

class ClearCacheEvent extends Event
{
    const CLEAR_EVENT = 'ck_cms.clear_cache';
    const GLOBAL_SCOPE = 'global';

    /** @var string */
    protected $scope;

    /**
     * {@inheritdoc}
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return string
     */
    public function getScope()
    {
        return $this->scope;
    }
}
