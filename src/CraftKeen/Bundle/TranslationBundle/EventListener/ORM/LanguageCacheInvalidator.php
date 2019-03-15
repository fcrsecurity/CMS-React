<?php

namespace CraftKeen\Bundle\TranslationBundle\EventListener\ORM;

use CraftKeen\Bundle\CacheBundle\Model\CacheChain;

class LanguageCacheInvalidator
{
    /** @var CacheChain */
    protected $cacheChain;

    /**
     * @param CacheChain $cacheChain
     */
    public function __construct(CacheChain $cacheChain)
    {
        $this->cacheChain = $cacheChain;
    }

    public function clearCache()
    {
        $this->cacheChain->deleteAll();
    }
}
