<?php

namespace CraftKeen\CMS\PageBundle\Entity;

interface SortableInterface
{
    /**
     * @return Object
     */
    public function getSortOrder();

}
