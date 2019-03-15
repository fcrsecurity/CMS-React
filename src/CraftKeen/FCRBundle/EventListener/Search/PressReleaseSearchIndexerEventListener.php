<?php

namespace CraftKeen\FCRBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\FCRBundle\Service\PressReleaseSearchConverter as Converter;

class PressReleaseSearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /** @var Converter */
    private $converter;

    /**
     * PressReleaseSearchIndexerEventListener constructor.
     * @param SearchIndexerInterface $searchIndexer
     * @param Converter $converter
     */
    public function __construct(SearchIndexerInterface $searchIndexer, Converter $converter)
    {
        $this->searchIndexer = $searchIndexer;
        $this->converter = $converter;
    }

    /**
     * @param PressRelease $pressRelease
     */
    public function postPersist(PressRelease $pressRelease)
    {
        if ( 'live' == $pressRelease->getStatus() && !$pressRelease->getIsHidden() ) {
            $this->searchIndexer->add($this->converter->convert($pressRelease));
        }
    }

    /**
     * @param PressRelease $pressRelease
     */
    public function postUpdate(PressRelease $pressRelease)
    {
        if ( 'live' == $pressRelease->getStatus() && !$pressRelease->getIsHidden() ) {
            $this->searchIndexer->add($this->converter->convert($pressRelease));
        }
    }

    /**
     * @param PressRelease $pressRelease
     */
    public function postRemove(PressRelease $pressRelease)
    {
        $this->searchIndexer->remove($this->converter->convert($pressRelease));
    }

}