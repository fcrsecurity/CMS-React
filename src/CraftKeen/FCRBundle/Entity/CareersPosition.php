<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;

/**
 * CareersPosition
 *
 * @ORM\Table(name="careers_position")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\CareersPositionRepository")
 */
class CareersPosition
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
     * @var Language
     *
     * Many Careers Position have One Language.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Language")
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     */
    private $lang;

    /**
     * @var Site
     *
     * Many Careers Position have One Site.
     * @ORM\ManyToOne(targetEntity="CraftKeen\CMS\AdminBundle\Entity\Site")
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    private $site;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title;
    
    /**
     * @var string
     *
     * @ORM\Column(name="code", type="string", length=255)
     */
    private $code;
    
    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="state", type="string", length=255)
     */
    private $state;

    /**
     * @var string
     *
     * @ORM\Column(name="category_name", type="string", length=255)
     */
    private $categoryName;


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
     * Set lang
     *
     * @param Language $lang
     *
     * @return CareersPosition
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
     * Set site
     *
     * @param Site $site
     *
     * @return CareersPosition
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
     * Set title
     *
     * @param string $title
     *
     * @return CareersPosition
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
     * Set city
     *
     * @param string $city
     *
     * @return CareersPosition
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set categoryName
     *
     * @param string $categoryName
     *
     * @return CareersPosition
     */
    public function setCategoryName($categoryName)
    {
        $this->categoryName = $categoryName;

        return $this;
    }

    /**
     * Get categoryName
     *
     * @return string
     */
    public function getCategoryName()
    {
        return $this->categoryName;
    }

    /**
     * Set code
     *
     * @param string $code
     *
     * @return CareersPosition
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return CareersPosition
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CareersPosition
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
}
