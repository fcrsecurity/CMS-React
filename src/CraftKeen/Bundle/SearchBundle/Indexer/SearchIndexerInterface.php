<?php

namespace CraftKeen\Bundle\SearchBundle\Indexer;

use CraftKeen\Bundle\SearchBundle\Model\SearchableInterface;

interface SearchIndexerInterface
{
    /**
     * @param SearchableInterface $item
     *
     * @return $this
     */
    public function add(SearchableInterface $item);

    /**
     * @param SearchableInterface $item
     *
     * @return $this
     */
    public function remove(SearchableInterface $item);
}
