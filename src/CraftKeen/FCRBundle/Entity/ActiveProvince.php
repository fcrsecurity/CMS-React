<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ActiveProvince
 *
 * @ORM\Table(name="leasing_active_province")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\ActiveProvinceRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class ActiveProvince extends BaseEntity
{
    use LeasingEntitiesPermissionsTrait;

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
     * @Gedmo\Versioned
     * @ORM\Column(name="province_code", type="string", length=2)
     * @Assert\Length(
     *      min = 2,
     *      max = 2,
     *      minMessage = "Province Code must to be least {{ limit }} characters long",
     *      maxMessage = "Province Code cannot be longer than {{ limit }} characters"
     * )
     */
    private $provinceCode;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="province_name", type="string", length=50)
     * @Assert\Length(
     *      min = 2,
     *      max = 50,
     *      minMessage = "Province Name must to be least {{ limit }} characters long",
     *      maxMessage = "Province Name cannot be longer than {{ limit }} characters"
     * )
     */
    private $provinceName;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="total_area", type="float")
     */
    private $totalArea;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="population", type="float")
     */
    private $population;

    /**
     * @var float
     * @Gedmo\Versioned
     * @ORM\Column(name="households", type="float")
     */
    private $households;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="icon", type="text")
     */
    private $icon;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="icon_width", type="text")
     */
    private $iconWidth;

    /**
     * @var float
     * @Gedmo\Versioned
     * @ORM\Column(name="description", type="text")
     */
    private $description;


    /**
     * @var float
     * @Gedmo\Versioned
     * @ORM\Column(name="label_lat", type="float")
     */
    private $labelLat;

    /**
     * @var string
     * @Gedmo\Versioned
     * @ORM\Column(name="label_lng", type="float")
     */
    private $labelLng;

    /**
     * @var float
     * @Gedmo\Versioned
     * @ORM\Column(name="marker_lat", type="float")
     */
    private $markerLat;

    /**
     * @var float
     * @Gedmo\Versioned
     * @ORM\Column(name="marker_lng", type="float")
     */
    private $markerLng;

    /**
     * @var ActiveProvince
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="ActiveProvince", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var ActiveProvince
     *
     * One ActiveProvince have One Copy of ActiveProvince.
     * @ORM\OneToOne(targetEntity="ActiveProvince", cascade={"persist"})
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
        return $this->provinceCode;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return ActiveProvince
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
     * Set provinceCode
     *
     * @param string $provinceCode
     *
     * @return ActiveProvince
     */
    public function setProvinceCode($provinceCode)
    {
        $this->provinceCode = $provinceCode;

        return $this;
    }

    /**
     * Get provinceCode
     *
     * @return string
     */
    public function getProvinceCode()
    {
        return $this->provinceCode;
    }

    /**
     * Set provinceName
     *
     * @param string $provinceName
     *
     * @return ActiveProvince
     */
    public function setProvinceName($provinceName)
    {
        $this->provinceName = $provinceName;

        return $this;
    }

    /**
     * Get provinceName
     *
     * @return string
     */
    public function getProvinceName()
    {
        return $this->provinceName;
    }

    /**
     * Set labelLat
     *
     * @param float $labelLat
     *
     * @return ActiveProvince
     */
    public function setLabelLat($labelLat)
    {
        $this->labelLat = $labelLat;

        return $this;
    }

    /**
     * Get labelLat
     *
     * @return float
     */
    public function getLabelLat()
    {
        return $this->labelLat;
    }

    /**
     * Set labelLng
     *
     * @param float $labelLng
     *
     * @return ActiveProvince
     */
    public function setLabelLng($labelLng)
    {
        $this->labelLng = $labelLng;

        return $this;
    }

    /**
     * Get labelLng
     *
     * @return float
     */
    public function getLabelLng()
    {
        return $this->labelLng;
    }

    /**
     * Set markerLat
     *
     * @param float $markerLat
     *
     * @return ActiveProvince
     */
    public function setMarkerLat($markerLat)
    {
        $this->markerLat = $markerLat;

        return $this;
    }

    /**
     * Get markerLat
     *
     * @return float
     */
    public function getMarkerLat()
    {
        return $this->markerLat;
    }

    /**
     * Set markerLng
     *
     * @param float $markerLng
     *
     * @return ActiveProvince
     */
    public function setMarkerLng($markerLng)
    {
        $this->markerLng = $markerLng;

        return $this;
    }

    /**
     * Get markerLng
     *
     * @return float
     */
    public function getMarkerLng()
    {
        return $this->markerLng;
    }

    /**
     * Set totalArea
     *
     * @param string $totalArea
     *
     * @return ActiveProvince
     */
    public function setTotalArea($totalArea)
    {
        $this->totalArea = $totalArea;

        return $this;
    }

    /**
     * Get totalArea
     *
     * @return string
     */
    public function getTotalArea()
    {
        return $this->totalArea;
    }

    /**
     * Set population
     *
     * @param string $population
     *
     * @return ActiveProvince
     */
    public function setPopulation($population)
    {
        $this->population = $population;

        return $this;
    }

    /**
     * Get population
     *
     * @return string
     */
    public function getPopulation()
    {
        return $this->population;
    }

    /**
     * Set households
     *
     * @param float $households
     *
     * @return ActiveProvince
     */
    public function setHouseholds($households)
    {
        $this->households = $households;

        return $this;
    }

    /**
     * Get households
     *
     * @return float
     */
    public function getHouseholds()
    {
        return $this->households;
    }

    /**
     * Set icon
     *
     * @param string $icon
     *
     * @return ActiveProvince
     */
    public function setIcon($icon)
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * Get icon
     *
     * @return string
     */
    public function getIcon()
    {
        return $this->icon;
    }

    /**
     * Set iconWidth
     *
     * @param string $iconWidth
     *
     * @return ActiveProvince
     */
    public function setIconWidth($iconWidth)
    {
        $this->iconWidth = $iconWidth;

        return $this;
    }

    /**
     * Get iconWidth
     *
     * @return string
     */
    public function getIconWidth()
    {
        return $this->iconWidth;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return ActiveProvince
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
     * @param ActiveProvince $langParent
     *
     * @return ActiveProvince
     */
    public function setLangParent(ActiveProvince $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return ActiveProvince
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param ActiveProvince $copyOf
     *
     * @return ActiveProvince
     */
    public function setCopyOf(ActiveProvince $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return ActiveProvince
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
            'provinceCode',
            'provinceName',
            'totalArea',
            'population',
            'households',
            'icon',
            'status'
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
     * Returns Entity Base Route
     *
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return 'admin_fcr_leasing_province_';
    }
}
