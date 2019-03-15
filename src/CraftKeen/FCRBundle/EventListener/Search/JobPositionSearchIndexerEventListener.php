<?php

namespace CraftKeen\FCRBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\FCRBundle\Entity\CareersPosition;
use CraftKeen\FCRBundle\Service\JobPositionSearchConverter as Converter;

class JobPositionSearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /** @var Converter */
    private $converter;

    /**
     * JobPositionSearchIndexerEventListener constructor.
     * @param SearchIndexerInterface $searchIndexer
     * @param Converter $converter
     */
    public function __construct(SearchIndexerInterface $searchIndexer, Converter $converter)
    {
        $this->searchIndexer = $searchIndexer;
        $this->converter = $converter;
    }

    /**
     * @param CareersPosition $careerPosition
     */
    public function postPersist(CareersPosition $careerPosition)
    {
        if ( 'ON' == $careerPosition->getState() ) {
            $this->searchIndexer->add($this->converter->convert($careerPosition));
        }
    }

    /**
     * @param CareersPosition $careerPosition
     */
    public function postUpdate(CareersPosition $careerPosition)
    {
        if ( 'ON' == $careerPosition->getState() ) {
            $this->searchIndexer->add($this->converter->convert($careerPosition));
        }
    }

    /**
     * @param CareersPosition $careerPosition
     */
    public function postRemove(CareersPosition $careerPosition)
    {
        $this->searchIndexer->remove($this->converter->convert($careerPosition));
    }

}
