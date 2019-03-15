<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PropertyGallery
 *
 * @ORM\Table(name="leasing_property_gallery")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyGalleryRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PropertyGallery
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
     * Many Images have One Property.
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="gallery")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text")
     * @Gedmo\Versioned
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_alt", type="string", length=150, nullable=true)
     * @Gedmo\Versioned
     */
    private $imageAlt;
    
    /**
     * @var int
     *
     * @ORM\Column(name="sort_order", type="integer", nullable=true)
     * @Gedmo\Versioned
     */
    private $sortOrder;

    /**
     * Set Id
     *
     * @param integer $id
     *
     * @return PropertyGallery
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Get vacantSqft
     *
     * @return string
     */
    public function __toString()
    {
        return $this->image;
    }
    
    /**
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyGallery
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get property
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
    }

    /**
     * Set imageAlt
     *
     * @param string $imageAlt
     *
     * @return PropertyGallery
     */
    public function setImageAlt($imageAlt)
    {
        $this->imageAlt = $imageAlt;

        return $this;
    }

    /**
     * Get imageAlt
     *
     * @return string
     */
    public function getImageAlt()
    {
        return $this->imageAlt;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PropertyGallery
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set sortOrder
     *
     * @param integer $sortOrder
     *
     * @return PropertyGallery
     */
    public function setSortOrder($sortOrder)
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    /**
     * Get sortOrder
     *
     * @return integer
     */
    public function getSortOrder()
    {
        return $this->sortOrder;
    }
}
