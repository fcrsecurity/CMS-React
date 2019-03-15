<?php

namespace CraftKeen\Bundle\CacheBundle\Tests\Unit\Model;

use CraftKeen\Bundle\CacheBundle\Model\CacheChain;
use Doctrine\Common\Cache\CacheProvider;

class CacheChainTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var CacheChain
     */
    protected $chain;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->chain = new CacheChain();
    }

    public function testDeleteAll(){
        $cache = $this->createMock(CacheProvider::class);
        $cache->expects($this->once())->method('deleteAll');
        $this->chain->addCacheProvider($cache);
        $this->chain->deleteAll();
    }

}
