<?php

namespace CraftKeen\FCRBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\FCRBundle\Entity\People;
use CraftKeen\FCRBundle\Service\PeopleSearchConverter as Converter;

class PeopleSearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /** @var Converter */
    private $converter;

    /**
     * PeopleSearchIndexerEventListener constructor.
     * @param SearchIndexerInterface $searchIndexer
     * @param Converter $converter
     */
    public function __construct(SearchIndexerInterface $searchIndexer, Converter $converter)
    {
        $this->searchIndexer = $searchIndexer;
        $this->converter = $converter;
    }

    /**
     * @param People $people
     */
    public function postPersist(People $people)
    {
        if ( 'live' == $people->getStatus() && is_null($people->getDeletedAt()) ) {
            $this->searchIndexer->add($this->converter->convert($people));
        }
    }

    /**
     * @param People $people
     */
    public function postUpdate(People $people)
    {
        if ( 'live' == $people->getStatus() && is_null($people->getDeletedAt()) ) {
            $this->searchIndexer->add($this->converter->convert($people));
        }
    }

    /**
     * @param People $people
     */
    public function postRemove(People $people)
    {
        $this->searchIndexer->remove($this->converter->convert($people));
    }

}
