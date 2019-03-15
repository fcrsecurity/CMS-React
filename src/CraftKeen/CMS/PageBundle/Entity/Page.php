<?php

namespace CraftKeen\CMS\PageBundle\Entity;

use CraftKeen\Bundle\ComponentBundle\Metadata\EntityConfig;
use CraftKeen\Bundle\ComponentBundle\Model\SluggableInterface;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use CraftKeen\FCRBundle\Entity\BaseEntity;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Table(name="page")
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\PageBundle\Repository\PageRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 * @EntityConfig(
 *     routes={
 *          "route_slug"="craftkeen_cms_page_inner"
 *     }
 * )
 */
class Page extends BaseEntity implements ScriptHolderInterface, SluggableInterface
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var Site
     *
     * Many Pages have One Site.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Site", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    protected $site;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=200, nullable=false)
     * @Gedmo\Versioned
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="scripts", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $scripts;

    /**
     * @var string
     *
     * @ORM\Column(name="hero", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $hero;

    /**
     * @var string
     *
     * @ORM\Column(name="hero_video", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $heroVideo;

    /**
     * @var string
     *
     * @ORM\Column(name="hero_title", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $heroTitle;

    /**
     * @var Page
     *
     * Many Pages have One Parent.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\PageBundle\Entity\Page", cascade={"detach"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $langParent;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_title", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $metaTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_description", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $metaDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="meta_keywords", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $metaKeywords;

    /**
     * @ORM\OneToMany(targetEntity="Page", mappedBy="parent")
     */
    protected $children;

    /**
     * @var Page
     *
     * Many Pages have One Parent.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\PageBundle\Entity\Page", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     * @Gedmo\Versioned
     */
    protected $parent;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_indexed", type="boolean", nullable=false)
     * @Gedmo\Versioned
     */
    protected $isIndexed;

    /**
     * @var string
     *
     * @ORM\Column(name="template", type="string", length=200, nullable=false)
     * @Gedmo\Versioned
     */
    protected $template;

    /**
     * @var string
     *
     * @ORM\Column(name="layout", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    protected $layout;

    /**
     * @var Route
     * @ORM\OneToMany(targetEntity="CraftKeen\CMS\PageBundle\Entity\Route", mappedBy="page", fetch="EXTRA_LAZY")
     */
    protected $routes;

    /**
     * @var Page
     *
     * One Page have One Copy of Page.
     * @ORM\OneToOne(targetEntity="Page", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new ArrayCollection();
        $this->routes = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
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
     * Set name
     *
     * @param string $name
     *
     * @return Page
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
     * Set scripts
     *
     * @param string $scripts
     *
     * @return Page
     */
    public function setScripts($scripts)
    {
        $this->scripts = $scripts;

        return $this;
    }

    /**
     * Get scripts
     *
     * @return string
     */
    public function getScripts()
    {
        return $this->scripts;
    }

    /**
     * Set hero
     *
     * @param string $hero
     *
     * @return Page
     */
    public function setHero($hero)
    {
        $this->hero = $hero;

        return $this;
    }

    /**
     * Get hero
     *
     * @return string
     */
    public function getHero()
    {
        return $this->hero;
    }

    /**
     * Set heroVideo
     *
     * @param string $heroVideo
     *
     * @return Page
     */
    public function setHeroVideo($heroVideo)
    {
        $this->heroVideo = $heroVideo;

        return $this;
    }

    /**
     * Get heroVideo
     *
     * @return string
     */
    public function getHeroVideo()
    {
        return $this->heroVideo;
    }

    /**
     * Set heroTitle
     *
     * @param string $heroTitle
     *
     * @return Page
     */
    public function setHeroTitle($heroTitle)
    {
        $this->heroTitle = $heroTitle;

        return $this;
    }

    /**
     * Get heroTitle
     *
     * @return string
     */
    public function getHeroTitle()
    {
        return $this->heroTitle;
    }

    /**
     * Set metaTitle
     *
     * @param string $metaTitle
     *
     * @return Page
     */
    public function setMetaTitle($metaTitle)
    {
        $this->metaTitle = $metaTitle;

        return $this;
    }

    /**
     * Get metaTitle
     *
     * @return string
     */
    public function getMetaTitle()
    {
        return $this->metaTitle;
    }

    /**
     * Set metaDescription
     *
     * @param string $metaDescription
     *
     * @return Page
     */
    public function setMetaDescription($metaDescription)
    {
        $this->metaDescription = $metaDescription;

        return $this;
    }

    /**
     * Get metaDescription
     *
     * @return string
     */
    public function getMetaDescription()
    {
        return $this->metaDescription;
    }

    /**
     * Set metaKeywords
     *
     * @param string $metaKeywords
     *
     * @return Page
     */
    public function setMetaKeywords($metaKeywords)
    {
        $this->metaKeywords = $metaKeywords;

        return $this;
    }

    /**
     * Get metaKeywords
     *
     * @return string
     */
    public function getMetaKeywords()
    {
        return $this->metaKeywords;
    }

    /**
     * Set isIndexed
     *
     * @param boolean $isIndexed
     *
     * @return Page
     */
    public function setIsIndexed($isIndexed)
    {
        $this->isIndexed = $isIndexed;

        return $this;
    }

    /**
     * Get isIndexed
     *
     * @return boolean
     */
    public function getIsIndexed()
    {
        return $this->isIndexed;
    }

    /**
     * Set template
     *
     * @param string $template
     *
     * @return Page
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get template
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }

    /**
     * Set layout
     *
     * @param string $layout
     *
     * @return Page
     */
    public function setLayout($layout)
    {
        $this->layout = $layout;

        return $this;
    }

    /**
     * Get layout
     *
     * @return string
     */
    public function getLayout()
    {
        return $this->layout;
    }

    /**
     * Set site
     *
     * @param Site $site
     *
     * @return Page
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
     * Set langParent
     *
     * @param Page $langParent
     *
     * @return Page
     */
    public function setLangParent(Page $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Page
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Add child
     *
     * @param Page $child
     *
     * @return Page
     */
    public function addChild(Page $child)
    {
        $this->children[] = $child;

        return $this;
    }

    /**
     * Remove child
     *
     * @param Page $child
     */
    public function removeChild(Page $child)
    {
        $this->children->removeElement($child);
    }

    /**
     * Get children
     *
     * @return Collection
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set parent
     *
     * @param Page $parent
     *
     * @return Page
     */
    public function setParent(Page $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Page
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set copyOf
     *
     * @param Page $copyOf
     *
     * @return Page
     */
    public function setCopyOf(Page $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Page
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * @return bool
     */
    public function isLive()
    {
        return $this->getStatus() === 'live';
    }

    /**
     * @return string|null
     */
    public function getSlug()
    {
        /** @var Route $route */
        foreach ($this->routes->toArray() as $route) {
            $slug = $route->getSlug();
            if ($slug) {
                return $slug;
            }
        }

        return null;
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
            'name',
            'heroTitle',
            'parent',
            'template',
            'status',
            'created',
            'updated',
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
}
