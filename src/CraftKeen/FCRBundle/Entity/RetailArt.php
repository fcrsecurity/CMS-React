<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * RetailArt
 *
 * @ORM\Table(name="community_retail_art")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\RetailArtRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class RetailArt extends BaseEntity
{
    use HrEntitiesPermissionsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Many Users have Many Groups.
     * @ORM\ManyToMany(targetEntity="RetailArtCategory", inversedBy="posts")
     * @ORM\JoinTable(
     *     name="community_retail_art__categories",
     *     joinColumns={@ORM\JoinColumn(name="retail_art_id", referencedColumnName="id", onDelete="cascade")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="category_id", referencedColumnName="id")}
     *     )
     */
    private $categories;

    /**
     * Many Posts have Many Gallery Items.
     * @ORM\ManyToMany(targetEntity="RetailArtGallery", cascade={"persist"})
     * @ORM\JoinTable(name="community_retail_art__gallery",
     *      joinColumns={@ORM\JoinColumn(name="post_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="gallery_id", referencedColumnName="id", unique=true)}
     *      )
     */
    private $gallery;

    /**
     * @var string
     *
     * @ORM\Column(name="head", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $head;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="text", type="text")
     * @Gedmo\Versioned
     */
    private $text;

    /**
     * @var string
     *
     * @ORM\Column(name="short", type="text")
     * @Gedmo\Versioned
     */
    private $short;

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
     * @ORM\Column(name="imageAlt", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $imageAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="class", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $class;

    /**
     * @var RetailArt
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="RetailArt", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var RetailArt
     *
     * One RetailArt have One Copy of RetailArt.
     * @ORM\OneToOne(targetEntity="RetailArt", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Property constructor
     */
    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->gallery = new ArrayCollection();
    }

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
     * {@inheritdoc}
     */
    public function __clone()
    {
        $this->id = null;
        $this
            ->setVersion(1)
            ->setStatus('draft')
            ->setCreated(new \DateTime())
            ->setUpdated(null);

        $galleries = $this->getGallery();

        $this->gallery = new ArrayCollection();
        foreach ($galleries as $gallery) {
            $cloneGallery = clone $gallery;
            $this->gallery->add($cloneGallery);
        }
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return RetailArt
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
     * Set category
     *
     * @param string $category
     *
     * @return RetailArt
     */
    public function setCategory($category)
    {
        $this->categories = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->categories;
    }

    /**
     * Set head
     *
     * @param string $head
     *
     * @return RetailArt
     */
    public function setHead($head)
    {
        $this->head = $head;

        return $this;
    }

    /**
     * Get head
     *
     * @return string
     */
    public function getHead()
    {
        return $this->head;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return RetailArt
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
     * Set text
     *
     * @param string $text
     *
     * @return RetailArt
     */
    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }

    /**
     * Get text
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    /**
     * Set short
     *
     * @param string $short
     *
     * @return RetailArt
     */
    public function setShort($short)
    {
        $this->short = $short;

        return $this;
    }

    /**
     * Get short
     *
     * @return string
     */
    public function getShort()
    {
        return $this->short;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return RetailArt
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
     * @return RetailArt
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
     * Set class
     *
     * @param string $class
     *
     * @return RetailArt
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set langParent
     *
     * @param RetailArt $langParent
     *
     * @return RetailArt
     */
    public function setLangParent(RetailArt $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return RetailArt
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Add category
     *
     * @param RetailArtCategory $category
     *
     * @return RetailArt
     */
    public function addCategory(RetailArtCategory $category)
    {
        $this->categories[] = $category;

        return $this;
    }

    /**
     * Remove category
     *
     * @param RetailArtCategory $category
     */
    public function removeCategory(RetailArtCategory $category)
    {
        $this->categories->removeElement($category);
    }

    /**
     * Get categories
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getCategories()
    {
        return $this->categories;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return RetailArt
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add gallery
     *
     * @param RetailArtGallery $gallery
     *
     * @return RetailArt
     */
    public function addGallery(RetailArtGallery $gallery)
    {
        $this->gallery[] = $gallery;

        return $this;
    }

    /**
     * Remove gallery
     *
     * @param RetailArtGallery $gallery
     */
    public function removeGallery(RetailArtGallery $gallery)
    {
        $this->gallery->removeElement($gallery);
    }

    /**
     * Get gallery
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getGallery()
    {
        return $this->gallery;
    }

    /**
     * Set copyOf
     *
     * @param RetailArt $copyOf
     *
     * @return RetailArt
     */
    public function setCopyOf(RetailArt $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return RetailArt
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
            'head',
            'slug',
            'title',
            'short',
            'image',
            'imageAlt',
            'class',
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
    public function getEntityBaseRoute() {
        return 'admin_community_retail-art_';
    }
}
