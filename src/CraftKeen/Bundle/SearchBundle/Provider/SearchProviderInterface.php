<?php

namespace CraftKeen\Bundle\SearchBundle\Provider;

interface SearchProviderInterface
{
    /**
     * @param $query
     * @param int $offset
     * @param null $limit
     *
     * @return mixed
     */
    public function search($query, $offset = 0, $limit = null);
}
