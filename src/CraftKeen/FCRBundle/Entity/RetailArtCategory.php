<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use CraftKeen\CMS\AdminBundle\Entity\Language;

/**
 * RetailArtCategory
 *
 * @ORM\Table(name="community_retail_art_category")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\RetailArtCategoryRepository")
 */
class RetailArtCategory
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     */
    private $slug;

    /**
     * Many Categories have Many Posts.
     *
     * @ORM\ManyToMany(targetEntity="RetailArt", mappedBy="categories")
     */
    private $posts;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="text", nullable=true)
     */
    private $image;

    /**
     * @var string
     *
     * @ORM\Column(name="imageAlt", type="string", length=255, nullable=true)
     */
    private $imageAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var Language
     *
     * Many Properties have One Language.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Language", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, referencedColumnName="id")
     */
    private $lang;

    /**
     * @var RetailArtCategory
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="RetailArtCategory", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=50, nullable=true, options={"default" : "live"})
     */
    protected $status;

    /**
     * @return array
     */
    public function toArray()
    {
        $r = new \ReflectionClass($this);
        $ps = $r->getProperties();
        $res = [];
        /** @var \ReflectionProperty $p */
        foreach ($ps as $p) {
            $p->setAccessible(true);

            if (!in_array($p->getName(), ['posts', 'langParent'])) {
                $res[$p->getName()] = (string)$p->getValue($this);
            }
        }
        return $res;
    }

    /**
     * RetailArtCategory constructor
     */
    public function __construct()
    {
        $this->posts = new ArrayCollection();
    }

    /**
     * Get Title
     *
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return RetailArtCategory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return RetailArtCategory
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
     * Set image
     *
     * @param string $image
     *
     * @return RetailArtCategory
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
     * @return RetailArtCategory
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
     * Set description
     *
     * @param string $description
     *
     * @return RetailArtCategory
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Add post
     *
     * @param RetailArt $post
     *
     * @return RetailArtCategory
     */
    public function addPost(RetailArt $post)
    {
        $this->posts[] = $post;

        return $this;
    }

    /**
     * Remove post
     *
     * @param RetailArt $post
     */
    public function removePost(RetailArt $post)
    {
        $this->posts->removeElement($post);
    }

    /**
     * Get posts
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPosts()
    {
        return $this->posts;
    }

    /**
     * Set lang
     *
     * @param Language $lang
     *
     * @return RetailArtCategory
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
     * Set langParent
     *
     * @param RetailArtCategory $langParent
     *
     * @return RetailArtCategory
     */
    public function setLangParent(RetailArtCategory $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return RetailArtCategory
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set status
     *
     * @param string $status
     *
     * @return RetailArtCategory
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return string
     */
    public function getEntityBaseRoute() {
        return 'admin_community_retail-art_category_';
    }
}
