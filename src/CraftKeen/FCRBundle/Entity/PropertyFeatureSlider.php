<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PropertyFeatureSlider
 *
 * @ORM\Table(name="property_feature_slider")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyFeatureSliderRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PropertyFeatureSlider extends BaseEntity
{
    use LeasingCoordinatorEntitiesPermissionsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @var Property
     *
     * Many Slides have One Property.
     * @ORM\ManyToOne(targetEntity="CraftKeen\FCRBundle\Entity\Property")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var Page
     *
     * Many Slides have One Page.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\PageBundle\Entity\Page")
     * @ORM\JoinColumn(name="page_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $page;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="image_alt", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $imageAlt;

    /**
     * @var PropertyFeatureSlider
     *
     * Many PropertyFeatureSlider have One Parent.
     * @ORM\ManyToOne(targetEntity="CraftKeen\FCRBundle\Entity\PropertyFeatureSlider", cascade={"detach"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $langParent;

    /**
     * @var PropertyFeatureSlider
     *
     * One PropertyFeatureSlider have One Copy of PropertyFeatureSlider.
     * @ORM\OneToOne(targetEntity="CraftKeen\FCRBundle\Entity\PropertyFeatureSlider", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Title
     *
     * @return string
     */
    public function __toString()
    {
        return $this->title;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return PropertyFeatureSlider
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
     * Set title
     *
     * @param string $title
     *
     * @return PropertyFeatureSlider
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PropertyFeatureSlider
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
     * Set imageAlt
     *
     * @param string $imageAlt
     *
     * @return PropertyFeatureSlider
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
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyFeatureSlider
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
     * Set page
     *
     * @param Page $page
     *
     * @return PropertyFeatureSlider
     */
    public function setPage(Page $page = null)
    {
        $this->page = $page;

        return $this;
    }

    /**
     * Get page
     *
     * @return Page
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * Set langParent
     *
     * @param PropertyFeatureSlider $langParent
     *
     * @return PropertyFeatureSlider
     */
    public function setLangParent(PropertyFeatureSlider $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return PropertyFeatureSlider
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param PropertyFeatureSlider $copyOf
     *
     * @return PropertyFeatureSlider
     */
    public function setCopyOf(PropertyFeatureSlider $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return PropertyFeatureSlider
     */
    public function getCopyOf()
    {
        return $this->copyOf;
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
            'title',
            'image',
            'status',
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
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return 'admin_leasing_property_feature-slider_';
    }
}
