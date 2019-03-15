<?php

namespace CraftKeen\CMS\PageBundle\Entity;

interface VersionableInterface
{
    /**
     * @return string[]|array
     */
    public function getStatus();
}
