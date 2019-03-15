<?php

namespace CraftKeen\FCRBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\FCRBundle\Entity\RetailArt;
use CraftKeen\FCRBundle\Service\BlogSearchConverter as Converter;

class BlogSearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /** @var Converter */
    private $converter;

    /**
     * BlogSearchIndexerEventListener constructor.
     * @param SearchIndexerInterface $searchIndexer
     * @param Converter $converter
     */
    public function __construct(SearchIndexerInterface $searchIndexer, Converter $converter)
    {
        $this->searchIndexer = $searchIndexer;
        $this->converter = $converter;
    }

    /**
     * @param RetailArt $retailArt
     */
    public function postPersist(RetailArt $retailArt)
    {
        if ( 'live' == $retailArt->getStatus() && is_null($retailArt->getDeletedAt()) ) {
            $this->searchIndexer->add($this->converter->convert($retailArt));
        }
    }

    /**
     * @param RetailArt $retailArt
     */
    public function postUpdate(RetailArt $retailArt)
    {
        if ( 'live' == $retailArt->getStatus() && is_null($retailArt->getDeletedAt()) ) {
            $this->searchIndexer->add($this->converter->convert($retailArt));
        }
    }

    /**
     * @param RetailArt $retailArt
     */
    public function postRemove(RetailArt $retailArt)
    {
        $this->searchIndexer->remove($this->converter->convert($retailArt));
    }

}
