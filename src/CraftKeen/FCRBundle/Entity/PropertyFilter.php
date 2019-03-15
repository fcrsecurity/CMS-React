<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PropertyFilter
 *
 * @ORM\Table(name="leasing_property_filter")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyFilterRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PropertyFilter
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var Property
     *
     * One Demographic has One Property.
     * @ORM\OneToOne(targetEntity="Property", inversedBy="filters", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_filter_grocery_anchored", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isFilterGroceryAnchored;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_filter_urban_retail", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isFilterUrbanRetail;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_filter_office_space", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isFilterOfficeSpace;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_filter_under_development", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    private $isFilterUnderDevelopment;

    public function __clone()
    {
        if ($this->id) {
            $this->setId(null);
        }
    }

    /**
     * Set Id
     *
     * @param integer $id
     *
     * @return PropertyFilter
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

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
     * Set isFilterGroceryAnchored
     *
     * @param boolean $isFilterGroceryAnchored
     *
     * @return PropertyFilter
     */
    public function setIsFilterGroceryAnchored($isFilterGroceryAnchored)
    {
        $this->isFilterGroceryAnchored = $isFilterGroceryAnchored;

        return $this;
    }

    /**
     * Get isFilterGroceryAnchored
     *
     * @return boolean
     */
    public function getIsFilterGroceryAnchored()
    {
        return $this->isFilterGroceryAnchored;
    }

    /**
     * Set isFilterUrbanRetail
     *
     * @param boolean $isFilterUrbanRetail
     *
     * @return PropertyFilter
     */
    public function setIsFilterUrbanRetail($isFilterUrbanRetail)
    {
        $this->isFilterUrbanRetail = $isFilterUrbanRetail;

        return $this;
    }

    /**
     * Get isFilterUrbanRetail
     *
     * @return boolean
     */
    public function getIsFilterUrbanRetail()
    {
        return $this->isFilterUrbanRetail;
    }

    /**
     * Set isFilterOfficeSpace
     *
     * @param boolean $isFilterOfficeSpace
     *
     * @return PropertyFilter
     */
    public function setIsFilterOfficeSpace($isFilterOfficeSpace)
    {
        $this->isFilterOfficeSpace = $isFilterOfficeSpace;

        return $this;
    }

    /**
     * Get isFilterOfficeSpace
     *
     * @return boolean
     */
    public function getIsFilterOfficeSpace()
    {
        return $this->isFilterOfficeSpace;
    }

    /**
     * Set isFilterUnderDevelopment
     *
     * @param boolean $isFilterUnderDevelopment
     *
     * @return PropertyFilter
     */
    public function setIsFilterUnderDevelopment($isFilterUnderDevelopment)
    {
        $this->isFilterUnderDevelopment = $isFilterUnderDevelopment;

        return $this;
    }

    /**
     * Get isFilterUnderDevelopment
     *
     * @return boolean
     */
    public function getIsFilterUnderDevelopment()
    {
        return $this->isFilterUnderDevelopment;
    }

    /**
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyFilter
     */
    public function setProperty(Property $property = null)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return Property
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Copy data from object
     *
     * @param PropertyFilter $from
     *
     * @return PropertyFilter
     */
    public function copyDataFrom(PropertyFilter $from)
    {
        $this->setIsFilterGroceryAnchored($from->getIsFilterGroceryAnchored());
        $this->setIsFilterUrbanRetail($from->getIsFilterUrbanRetail());
        $this->setIsFilterOfficeSpace($from->getIsFilterOfficeSpace());
        $this->setIsFilterUnderDevelopment($from->getIsFilterUnderDevelopment());

        return $this;
    }
}
