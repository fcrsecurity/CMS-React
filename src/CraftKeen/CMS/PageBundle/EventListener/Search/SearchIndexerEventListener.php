<?php

namespace CraftKeen\CMS\PageBundle\EventListener\Search;

use CraftKeen\Bundle\SearchBundle\Indexer\SearchIndexerInterface;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Symfony\Component\DependencyInjection\ContainerInterface;
use CraftKeen\FCRBundle\Service\PageSearchConverter as Converter;

class SearchIndexerEventListener
{
    /** @var SearchIndexerInterface */
    protected $searchIndexer;

    /** @var Converter  */
    private $converter;

    /**
     * @param SearchIndexerInterface $searchIndexer
     * @param ContainerInterface $container
     */
    public function __construct(SearchIndexerInterface $searchIndexer, ContainerInterface $container)
    {
        $this->searchIndexer = $searchIndexer;
        $this->converter = $container->get('craft_keen.page.search.converter');
    }

    /**
     * @param Page $page
     */
    public function postPersist(Page $page)
    {
        $this->searchIndexer->add($this->converter->convert($page));
    }

    /**
     * @param Page $page
     */
    public function postUpdate(Page $page)
    {
        $this->searchIndexer->add($this->converter->convert($page));
    }

    /**
     * @param Page $page
     */
    public function postRemove(Page $page)
    {
        $this->searchIndexer->remove($this->converter->convert($page));
    }

}
