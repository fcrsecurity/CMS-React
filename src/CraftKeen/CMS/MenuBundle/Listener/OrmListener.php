<?php

namespace CraftKeen\CMS\MenuBundle\Listener;

use CraftKeen\Bundle\CacheBundle\Event\ClearCacheEvent;
use Doctrine\Common\Cache\CacheProvider;

class OrmListener
{
    /** @var CacheProvider */
    protected $cacheProvider;

    /**
     * @param CacheProvider $cacheProvider
     */
    public function __construct(CacheProvider $cacheProvider)
    {
        $this->cacheProvider = $cacheProvider;
    }

    public function invalidateCache()
    {
        $event = func_get_arg(0);

        if ($event instanceof ClearCacheEvent) {
            if (!in_array($event->getScope(), ['menu', ClearCacheEvent::GLOBAL_SCOPE])) {
                return;
            }
        }

        $this->cacheProvider->deleteAll();
    }
}
