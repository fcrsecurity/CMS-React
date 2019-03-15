<?php

namespace CraftKeen\CMS\PageBundle\Entity;

interface TranslatableInterface
{
    /**
     * @return Object
     */
    public function getLang();

    /**
     * @return Object
     */
    public function getLangParent();
}
