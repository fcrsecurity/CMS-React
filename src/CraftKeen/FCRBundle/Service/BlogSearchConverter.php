<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\RetailArt;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;

/**
 * @package CraftKeen\FCRBundle\Service
 *
 */
class BlogSearchConverter
{
    /**
     * @param RetailArt $retailArt
     *
     * @return SearchItem
     */
    public function convert(RetailArt $retailArt)
    {
        $model = new SearchItem();
        
        $body = $retailArt->getText() . "<br/>" . $retailArt->getHead() . "<br/>" . $retailArt->getShort();

        return $model->setWeight($retailArt->getSortOrder())
            ->setLanguage($retailArt->getLang())
            ->setSite($retailArt->getSite())
            ->setTitle($retailArt->getTitle())
            ->setObjectClass(RetailArt::class)
            ->setObjectId($retailArt->getId())
            ->setShortBody($body)
            ->setHiddenMeta($retailArt->getSlug())
            ;
    }
}
