<?php

namespace CraftKeen\Bundle\CacheBundle\Model;

use Doctrine\Common\Cache\CacheProvider;

class CacheChain
{
    /** @var CacheProvider[] */
    protected $cacheProviders = [];

    /**
     * @param CacheProvider $cacheProvider
     *
     * @return $this
     */
    public function addCacheProvider(CacheProvider $cacheProvider)
    {
        $this->cacheProviders[] = $cacheProvider;

        return $this;
    }

    public function deleteAll()
    {
        foreach ($this->cacheProviders as $cacheProvider) {
            $cacheProvider->deleteAll();
        }
    }
}
