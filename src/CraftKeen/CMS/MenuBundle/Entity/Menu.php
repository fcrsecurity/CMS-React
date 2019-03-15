<?php

namespace CraftKeen\CMS\MenuBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CraftKeen\FCRBundle\Entity\BaseEntity;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Menu
 *
 * @ORM\Table(name="menu")
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\MenuBundle\Repository\MenuRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Menu extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=300, nullable=false)
     * @Gedmo\Versioned
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=500, nullable=true)
     * @Gedmo\Versioned
     */
    protected $url;

    /**
     * @var Page
     *
     * Many Menu Items have One Page.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\PageBundle\Entity\Page")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    protected $page;

    /**
     * @var MenuType
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\MenuBundle\Entity\MenuType")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    protected $type;

    /**
     * @var string
     *
     * @ORM\Column(name="item_type", type="string", length=150, nullable=false)
     * @Gedmo\Versioned
     */
    protected $itemType;

    /**
     * @var boolean
     *
     * @ORM\Column(name="targetBlank", type="boolean", nullable=true)
     * @Gedmo\Versioned
     */
    protected $targetBlank;

    /**
     * @var Menu
     *
     * Many Menu Items have One Menu Parent.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\MenuBundle\Entity\Menu")
     * @ORM\JoinColumn(nullable=true)
     * @Gedmo\Versioned
     */
    protected $parent;

    /**
     * @var Menu
     *
     * Many Menu Items have One Parent.
     * @ORM\ManyToOne(targetEntity="\CraftKeen\CMS\MenuBundle\Entity\Menu", cascade={"detach"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $langParent;

    /**
     * @var Menu
     *
     * One Menu Item have One Copy of Menu.
     * @ORM\OneToOne(targetEntity="\CraftKeen\CMS\MenuBundle\Entity\Menu", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

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
     * @return Menu
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
     * Set url
     *
     * @param string $url
     *
     * @return Menu
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set itemType
     *
     * @param string $itemType
     *
     * @return Menu
     */
    public function setItemType($itemType)
    {
        $this->itemType = $itemType;

        return $this;
    }

    /**
     * Get itemType
     *
     * @return string
     */
    public function getItemType()
    {
        return $this->itemType;
    }

    /**
     * Set targetBlank
     *
     * @param boolean $targetBlank
     *
     * @return Menu
     */
    public function setTargetBlank($targetBlank)
    {
        $this->targetBlank = $targetBlank;

        return $this;
    }

    /**
     * Get targetBlank
     *
     * @return boolean
     */
    public function getTargetBlank()
    {
        return $this->targetBlank;
    }

    /**
     * Set page
     *
     * @param Page $page
     *
     * @return Menu
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
     * Set type
     *
     * @param MenuType $type
     *
     * @return Menu
     */
    public function setType(MenuType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return MenuType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set parent
     *
     * @param Menu $parent
     *
     * @return Menu
     */
    public function setParent(Menu $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return Menu
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Set langParent
     *
     * @param Menu $langParent
     *
     * @return Menu
     */
    public function setLangParent(Menu $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Menu
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * @param $display
     * @deprecated Should not be used in CRUD
     * @return array
     */
    protected function getExcludeItems($display)
    {
        switch ($display) {
            case 'index':
                return [
                    'langParent',
                    'deletedAt',
                    'copyOf',
                    'version',
                    'versionComment',
                    'created',
                    'updated',
                    'updatedBy',
                    'access',
                    'parent',
                    'url',
                    'page',
                ];
                break;
            case 'view':
                return [
                    'langParent',
                    'deletedAt',
                    'copyOf',
                    'version',
                    'deletedAt',
                ];
                break;
        }

        return [];
    }

    /**
     * Set copyOf
     *
     * @param Menu $copyOf
     *
     * @return Menu
     */
    public function setCopyOf(Menu $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Menu
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * @return null|string
     */
    final public function getMenuLink()
    {
        $link = '/';

        switch ($this->getItemType()) {
            case 'page':
                $page = $this->getPage();

                if (!$page || $page->getStatus() != 'live') {
                    return null;
                }

                // Implement fallback to original Page Route if transaction doesn't have a route.
                if (null == $page->getSlug()) {
                    $link = $page->getLangParent()->getSlug() ?: '/';
                } else {
                    $link = $page->getSlug() ?: '/';
                }

                if ('/' !== $link) {
                    $link = str_replace('//', '/', $link);
                }

                break;
            case 'custom':
                $link = $this->getUrl() ?: '/';

                if (stripos($link, 'http') === false) {
                    $link = str_replace('//', '/', $link);
                }
                break;
        }

        return $link;
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
            'itemType',
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
}
