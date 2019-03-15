<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * CareersSlider
 *
 * @ORM\Table(name="careers_slider")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\CareersSliderRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class CareersSlider extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="background", type="text")
     * @Gedmo\Versioned
     */
    private $background;

    /**
     * @var string
     *
     * @ORM\Column(name="fancy_number", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $fancyNumber;

    /**
     * @var string
     *
     * @ORM\Column(name="header", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var CareersSlider
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="CareersSlider", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var CareersSlider
     *
     * One CareersSlider have One Copy of CareersSlider.
     * @ORM\OneToOne(targetEntity="CareersSlider", cascade={"persist"})
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
        return $this->header;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return CareersSlider
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
     * Set background
     *
     * @param string $background
     *
     * @return CareersSlider
     */
    public function setBackground($background)
    {
        $this->background = $background;

        return $this;
    }

    /**
     * Get background
     *
     * @return string
     */
    public function getBackground()
    {
        return $this->background;
    }

    /**
     * Set fancyNumber
     *
     * @param string $fancyNumber
     *
     * @return CareersSlider
     */
    public function setFancyNumber($fancyNumber)
    {
        $this->fancyNumber = $fancyNumber;

        return $this;
    }

    /**
     * Get fancyNumber
     *
     * @return string
     */
    public function getFancyNumber()
    {
        return $this->fancyNumber;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return CareersSlider
     */
    public function setHeader($header)
    {
        $this->header = $header;

        return $this;
    }

    /**
     * Get header
     *
     * @return string
     */
    public function getHeader()
    {
        return $this->header;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return CareersSlider
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
     * Set langParent
     *
     * @param CareersSlider $langParent
     *
     * @return CareersSlider
     */
    public function setLangParent(CareersSlider $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return CareersSlider
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param CareersSlider $copyOf
     *
     * @return CareersSlider
     */
    public function setCopyOf(CareersSlider $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return CareersSlider
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
            'background',
            'fancyNumber',
            'header',
            'description',
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
        return 'admin_careers_slider_';
    }
}
