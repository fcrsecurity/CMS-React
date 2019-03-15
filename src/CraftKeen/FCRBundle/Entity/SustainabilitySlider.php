<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * SustainabilitySlider
 *
 * @ORM\Table(name="sustainability_slider")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\SustainabilitySliderRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class SustainabilitySlider extends BaseEntity
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
     * @ORM\Column(name="number", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $number;

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
     * @ORM\Column(name="description", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var SustainabilitySlider
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="SustainabilitySlider", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var SustainabilitySlider
     *
     * One SustainabilitySlider have One Copy of SustainabilitySlider.
     * @ORM\OneToOne(targetEntity="SustainabilitySlider", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Title of the Slider
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
     * @return SustainabilitySlider
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
     * Set number
     *
     * @param string $number
     *
     * @return SustainabilitySlider
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set header
     *
     * @param string $header
     *
     * @return SustainabilitySlider
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
     * @return SustainabilitySlider
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
     * @param SustainabilitySlider $langParent
     *
     * @return SustainabilitySlider
     */
    public function setLangParent(SustainabilitySlider $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return SustainabilitySlider
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param SustainabilitySlider $copyOf
     *
     * @return SustainabilitySlider
     */
    public function setCopyOf(SustainabilitySlider $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return SustainabilitySlider
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
            'number',
            'header',
            'status',
            'updatedBy',
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
        return 'admin_community_sustainability_slider_';
    }
}
