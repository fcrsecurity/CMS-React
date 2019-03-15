<?php

namespace CraftKeen\Bundle\SearchBundle\Model;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;

interface SearchableInterface
{
    /**
     * @return string
     */
    public function getObjectClass();

    /**
     * @return int
     */
    public function getObjectId();

    /**
     * @return string
     */
    public function getTitle();

    /**
     * @return string
     */
    public function getShortBody();

    /**
     * @return string
     */
    public function getHiddenMeta();

    /**
     * @return int
     */
    public function getWeight();

    /**
     * @return Site
     */
    public function getSite();

    /**
     * @return Language
     */
    public function getLanguage();

    /**
     * @return mixed
     */
    public function getObject();

    /**
     * @param mixed $object
     *
     * @return $this
     */
    public function setObject($object);
}
