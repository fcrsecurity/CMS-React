<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\CareersPosition;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;

/**
 * @package CraftKeen\FCRBundle\Service
 *
 */
class JobPositionSearchConverter
{
    /**
     * @param CareersPosition $careersPosition
     *
     * @return SearchItem
     */
    public function convert(CareersPosition $careersPosition)
    {
        $model = new SearchItem();

        $body = $careersPosition->getDescription() . "<br/>" . $careersPosition->getCity() . "<br/>" .
            $careersPosition->getCategoryName();

        return $model->setWeight($careersPosition->getId())
            ->setLanguage($careersPosition->getLang())
            ->setSite($careersPosition->getSite())
            ->setTitle($careersPosition->getTitle())
            ->setObjectClass(CareersPosition::class)
            ->setObjectId($careersPosition->getId())
            ->setShortBody($body)
            ->setHiddenMeta($careersPosition->getCode())
            ;
    }
}
