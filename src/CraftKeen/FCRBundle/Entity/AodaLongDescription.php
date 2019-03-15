<?php

namespace CraftKeen\FCRBundle\Entity;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\ORM\Mapping as ORM;

/**
 * AodaLongDescription
 *
 * @ORM\Table(name="aoda_long_description", indexes={@ORM\Index(name="site_id", columns={"site_id"}), @ORM\Index(name="language_id", columns={"language_id"})})
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\AodaLongDescriptionRepository")
 */
class AodaLongDescription
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="object_class", type="text", nullable=false)
     */
    private $objectClass;

    /**
     * @var integer
     *
     * @ORM\Column(name="object_id", type="integer", nullable=false)
     */
    private $objectId;

    /**
     * @var string
     *
     * @ORM\Column(name="field_name", type="string", length=25, nullable=false)
     */
    private $fieldName;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type = "text";

    /**
     * @var string
     *
     * @ORM\Column(name="long_description", type="text", nullable=false)
     */
    private $longDescription;

    /**
     * @var Site
     *
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Site")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="site_id", referencedColumnName="id")
     * })
     */
    private $site;

    /**
     * @var Language
     *
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Language")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="language_id", referencedColumnName="id")
     * })
     */
    private $lang;

    /**
     * @var string
     *
     * @ORM\Column(name="access", type="text", nullable=true)
     */
    protected $access;

    /** * @var string
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     */
    private $sortOrder;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set site
     *
     * @param Site $site
     *
     * @return AodaLongDescription
     */
    public function setSite(Site $site = null)
    {
        $this->site = $site;

        return $this;
    }

    /**
     * Get site
     *
     * @return Site
     */
    public function getSite()
    {
        return $this->site;
    }

    /**
     * Set lang
     *
     * @param Language $lang
     *
     * @return AodaLongDescription
     */
    public function setLang(Language $lang)
    {
        $this->lang = $lang;

        return $this;
    }

    /**
     * Get lang
     *
     * @return Language
     */
    public function getLang()
    {
        return $this->lang;
    }

    /**
     * Set objectClass
     *
     * @param string $objectClass
     *
     * @return AodaLongDescription
     */
    public function setObjectClass($objectClass)
    {
        $this->objectClass = $objectClass;

        return $this;
    }

    /**
     * Get objectClass
     *
     * @return string
     */
    public function getObjectClass()
    {
        return $this->objectClass;
    }

    /**
     * Set objectId
     *
     * @param integer $objectId
     *
     * @return AodaLongDescription
     */
    public function setObjectId($objectId)
    {
        $this->objectId = $objectId;

        return $this;
    }

    /**
     * Get objectId
     *
     * @return integer
     */
    public function getObjectId()
    {
        return $this->objectId;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return AodaLongDescription
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set fieldName
     *
     * @param string $fieldName
     *
     * @return AodaLongDescription
     */
    public function setFieldName($fieldName)
    {
        $this->fieldName = $fieldName;

        return $this;
    }

    /**
     * Get fieldName
     *
     * @return string
     */
    public function getFieldName()
    {
        return $this->fieldName;
    }

    /**
     * Set longDescription
     *
     * @param string $longDescription
     *
     * @return AodaLongDescription
     */
    public function setLongDescription($longDescription)
    {
        $this->longDescription = $longDescription;

        return $this;
    }

    /**
     * Get longDescription
     *
     * @return string
     */
    public function getLongDescription()
    {
        return $this->longDescription;
    }

    /**
     * Set access
     *
     * @param string $access
     *
     * @return AodaLongDescription
     */
    public function setAccess($access)
    {
        $this->access = $access;

        return $this;
    }

    /**
     * Get access
     *
     * @return string
     */
    public function getAccess()
    {
        return $this->access;
    }

    /**
     * Set sortOrder
     *
     * @param string $sortOrder
     *
     * @return AodaLongDescription
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return string
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }

    /**
     * Return showing fields
     *
     * @param $display
     *
     * @return array
     */
    public function getDisplayItems($display)
    {
        $baseList = [
            'id',
            'objectClass',
            'objectId',
            'fieldName',
            'longDescription',
            'type'
        ];
        switch ($display) {
            case 'index':
                return $baseList;
                break;
            case 'view':
                return $baseList;
                break;
            case 'translate':
                return $baseList;
                break;
        }
        return [];
    }

    /**
     * Returns Entity Base Route
     *
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return 'admin_fcr_aoda_long_description_';
    }
}

