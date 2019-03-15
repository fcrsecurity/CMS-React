<?php

namespace CraftKeen\Bundle\SearchBundle\Model;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;

/**
 * Class SearchItem
 * @package CraftKeen\Bundle\SearchBundle\Model
 */
class SearchItem implements SearchableInterface
{
    /**
     * @var mixed
     */
    protected $object;

    /**
     * @var string
     */
    protected $objectClass;

    /**
     * @var int
     */
    protected $objectId;

    /**
     * @var Site
     */
    protected $site;

    /**
     * @var Language
     */
    protected $language;

    /**
     * @var int
     */
    protected $weight = 0;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $shortBody;

    /**
     * @var string
     */
    protected $hiddenMeta;

    /**
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * @param string $objectClass
     *
     * @return $this
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * @return int
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * @param int $objectId
     *
     * @return $this
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * @param Site $site
     *
     * @return $this
     */
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * @return Language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * @param Language $language
     *
     * @return $this
     */
    public function setLanguage($language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $weight
     *
     * @return $this
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string
     */
    public function getShortBody()
    {
        return $this->shortBody;
    }

    /**
     * @param string $shortBody
     *
     * @return $this
     */
    public function setShortBody($shortBody)
    {
        $this->shortBody = $shortBody;

        return $this;
    }

    /**
     * @return string
     */
    public function getHiddenMeta()
    {
        return $this->hiddenMeta;
    }

    /**
     * @param string $hiddenMeta
     *
     * @return $this
     */
    public function setHiddenMeta($hiddenMeta)
    {
        $this->hiddenMeta = $hiddenMeta;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param mixed $object
     *
     * @return $this
     */
    public function setObject($object)
    {
        $this->object = $object;

        return $this;
    }
}
