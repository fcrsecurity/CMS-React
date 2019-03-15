<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;


/**
 * PressRelease
 *
 * @ORM\Table(name="investors_press_release")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PressReleaseRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PressRelease extends BaseEntity
{
    use InvestorsEntitiesPermissionsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="datetime")
     * @Gedmo\Versioned
     */
    private $date;

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
     * @Gedmo\Slug(fields={"title"}, updatable=false, suffix="-draft", unique=true)
     * })
     * @ORM\Column(name="slug", type="string", length=255, unique=true)
     * @Gedmo\Versioned
     */
    private $slug;

    /**
     * @var string
     *
     * @ORM\Column(name="content", type="text", nullable=false)
     * @Gedmo\Versioned
     */
    private $content;

    /**
     * @var string
     *
     * @ORM\Column(name="pdfFile", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $pdfFile;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_hidden", type="boolean")
     * @Gedmo\Versioned
     */
    private $isHidden;

    /**
     * @var PressRelease
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="PressRelease", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var PressRelease
     *
     * One PressRelease have One Copy of PressRelease.
     * @ORM\OneToOne(targetEntity="PressRelease", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * {@inheritdoc}
     */
    public function __clone()
    {
        $this->id = null;
        $this->setStatus('draft');
    }

    /**
     * Get childName
     *
     * @return string
     */
    public function __toString()
    {
        return $this->title;
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return PressRelease
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PressRelease
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
     * Set content
     *
     * @param string $content
     *
     * @return PressRelease
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set pdfFile
     *
     * @param string $pdfFile
     *
     * @return PressRelease
     */
    public function setPdfFile($pdfFile)
    {
        $this->pdfFile = $pdfFile;

        return $this;
    }

    /**
     * Get pdfFile
     *
     * @return string
     */
    public function getPdfFile()
    {
        return $this->pdfFile;
    }

    /**
     * Set isHidden
     *
     * @param boolean $isHidden
     *
     * @return PressRelease
     */
    public function setIsHidden($isHidden)
    {
        $this->isHidden = $isHidden;

        return $this;
    }

    /**
     * Get isHidden
     *
     * @return bool
     */
    public function getIsHidden()
    {
        return $this->isHidden;
    }

    /**
     * Set slug
     *
     * @param string $slug
     *
     * @return PressRelease
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
     * Get langParent
     *
     * @return PressRelease
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set langParent
     *
     * @param PressRelease $langParent
     *
     * @return PressRelease
     */
    public function setLangParent(PressRelease $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Set copyOf
     *
     * @param PressRelease $copyOf
     *
     * @return PressRelease
     */
    public function setCopyOf(PressRelease $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return PressRelease
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
            'date',
            'title',
            'slug',
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
        return 'admin_leasing_press-release_';
    }
}
