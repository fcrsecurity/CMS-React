<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\People;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;

/**
 * @package CraftKeen\FCRBundle\Service
 *
 */
class PeopleSearchConverter
{
    /**
     * @param People $people
     *
     * @return SearchItem
     */
    public function convert(People $people)
    {
        $model = new SearchItem();

        $body = $people->getDescription() . "<br/>" . $people->getPosition();

        return $model->setWeight($people->getSortOrder())
            ->setLanguage($people->getLang())
            ->setSite($people->getSite())
            ->setTitle($people->getName())
            ->setObjectClass(People::class)
            ->setObjectId($people->getId())
            ->setShortBody($body)
            ->setHiddenMeta($people->getImage())
            ;
    }
}
