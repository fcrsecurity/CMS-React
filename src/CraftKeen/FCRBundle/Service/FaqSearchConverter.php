<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\FAQ;
use CraftKeen\Bundle\SearchBundle\Model\SearchItem;

/**
 * @package CraftKeen\FCRBundle\Service
 *
 */
class FaqSearchConverter
{
    /**
     * @param FAQ $faq
     *
     * @return SearchItem
     */
    public function convert(FAQ $faq)
    {
        $model = new SearchItem();

        return $model->setWeight($faq->getSortOrder())
            ->setLanguage($faq->getLang())
            ->setSite($faq->getSite())
            ->setTitle($faq->getQuestion())
            ->setObjectClass(FAQ::class)
            ->setObjectId($faq->getId())
            ->setShortBody($faq->getAnswer())
            ->setHiddenMeta($faq->getId())
            ;
    }
}
