<?php

namespace CraftKeen\FCRBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\FCRBundle\Entity\FAQ;
use CraftKeen\FCRBundle\Service\FaqSearchConverter as Converter;

class FaqSearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /** @var Converter */
    private $converter;

    /**
     * FaqSearchIndexerEventListener constructor.
     * @param SearchIndexerInterface $searchIndexer
     * @param Converter $converter
     */
    public function __construct(SearchIndexerInterface $searchIndexer, Converter $converter)
    {
        $this->searchIndexer = $searchIndexer;
        $this->converter = $converter;
    }

    /**
     * @param FAQ $faq
     */
    public function postPersist(FAQ $faq)
    {
        if ( 'live' == $faq->getStatus() && is_null($faq->getDeletedAt()) ) {
            $this->searchIndexer->add($this->converter->convert($faq));
        }
    }

    /**
     * @param FAQ $faq
     */
    public function postUpdate(FAQ $faq)
    {
        if ( 'live' == $faq->getStatus() && is_null($faq->getDeletedAt()) ) {
            $this->searchIndexer->add($this->converter->convert($faq));
        }
    }

    /**
     * @param FAQ $faq
     */
    public function postRemove(FAQ $faq)
    {
        $this->searchIndexer->remove($this->converter->convert($faq));
    }

}
