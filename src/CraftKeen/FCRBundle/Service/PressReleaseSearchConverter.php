<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;

/**
 * @package CraftKeen\FCRBundle\Service
 *
 */
class PressReleaseSearchConverter
{
	/**
     * @param PressRelease $pressRelease
     *
     * @return SearchItem
     */
	public function convert(PressRelease $pressRelease) 
    {
        $model = new SearchItem();

        $weight = ( is_null( $pressRelease->getSortOrder() ) ? 0 : $pressRelease->getSortOrder() );

        return $model->setWeight($weight)
            ->setLanguage($pressRelease->getLang())
            ->setSite($pressRelease->getSite())
            ->setTitle($pressRelease->getTitle())
            ->setObjectClass(PressRelease::class)
            ->setObjectId($pressRelease->getId())
            ->setShortBody($pressRelease->getContent())
            ->setHiddenMeta($pressRelease->getSlug())
            ;    
    }
}