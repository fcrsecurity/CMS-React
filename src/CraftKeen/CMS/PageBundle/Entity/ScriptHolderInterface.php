<?php

namespace CraftKeen\CMS\PageBundle\Entity;

interface ScriptHolderInterface
{
    /**
     * @return string[]|array
     */
    public function getScripts();
}
