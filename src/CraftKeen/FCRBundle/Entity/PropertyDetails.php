<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PropertyDetails
 *
 * @ORM\Table(name="leasing_property_details")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyDetailsRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PropertyDetails
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
     * @var Property
     *
     * One Details has One Property.
     * @ORM\OneToOne(targetEntity="Property", inversedBy="details", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var string
     *
     * @ORM\Column(name="sqft", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $sqft;

    /**
     * @var string
     *
     * @ORM\Column(name="vacant_sqft", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $vacantSqft;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="leed_description", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $leedDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="marketing_pdf", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $marketingPdf;

    /**
     * @var string
     *
     * @ORM\Column(name="site_plan_pdf", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $sitePlanPdf;

    /**
     * @var string
     *
     * @ORM\Column(name="video_url", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $videoUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="hero_image", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $heroImage;

    /**
     * @var string
     *
     * @ORM\Column(name="hero_image_alt", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $heroImageAlt;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_address_1", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoAddress1;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_address_2", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoAddress2;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_city", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoCity;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_province", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoProvince;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_province_region", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoProvinceRegion;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_country", type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoCountry;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_postal", type="string", length=10, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoPostal;

    /**
     * @var string
     *
     * @ORM\Column(name="geo_intersetion", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $geoIntersetion;

    /**
     * @var float
     *
     * @ORM\Column(name="geo_lat", type="float", nullable=true)
     * @Gedmo\Versioned
     */
    private $geoLat;

    /**
     * @var float
     *
     * @ORM\Column(name="geo_lng", type="float", nullable=true)
     * @Gedmo\Versioned
     */
    private $geoLng;

    /**
     * @var float
     *
     * @ORM\Column(name="social_facebook", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $socialFacebook;

    /**
     * @var string
     *
     * @ORM\Column(name="social_twitter", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $socialTwitter;

    /**
     * @var string
     *
     * @ORM\Column(name="social_url", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $socialUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_title", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $seoTitle;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_description", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $seoDescription;

    /**
     * @var string
     *
     * @ORM\Column(name="seo_keywords", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $seoKeywords;

    /**
     * @var bool
     *
     * @ORM\Column(name="seo_is_index", type="boolean")
     * @Gedmo\Versioned
     */
    private $seoIsIndex;

    /**
     * Clone PropertyDetails
     */
    public function __clone()
    {
        if ($this->id) {
            $this->setId(null);
        }
    }

    /**
     * Set Id
     *
     * @param integer $id
     *
     * @return PropertyDetails
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
     * Set sqft
     *
     * @param string $sqft
     *
     * @return PropertyDetails
     */
    public function setSqft($sqft)
    {
        $this->sqft = $sqft;

        return $this;
    }

    /**
     * Get sqft
     *
     * @return string
     */
    public function getSqft()
    {
        return $this->sqft;
    }

    /**
     * Set vacantSqft
     *
     * @param string $vacantSqft
     *
     * @return PropertyDetails
     */
    public function setVacantSqft($vacantSqft)
    {
        $this->vacantSqft = $vacantSqft;

        return $this;
    }

    /**
     * Get vacantSqft
     *
     * @return string
     */
    public function getVacantSqft()
    {
        return $this->vacantSqft;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PropertyDetails
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
     * Set leedDescription
     *
     * @param string $leedDescription
     *
     * @return PropertyDetails
     */
    public function setLeedDescription($leedDescription)
    {
        $this->leedDescription = $leedDescription;

        return $this;
    }

    /**
     * Get leedDescription
     *
     * @return string
     */
    public function getLeedDescription()
    {
        return $this->leedDescription;
    }

    /**
     * Set marketingPdf
     *
     * @param string $marketingPdf
     *
     * @return PropertyDetails
     */
    public function setMarketingPdf($marketingPdf)
    {
        $this->marketingPdf = $marketingPdf;

        return $this;
    }

    /**
     * Get marketingPdf
     *
     * @return string
     */
    public function getMarketingPdf()
    {
        return $this->marketingPdf;
    }

    /**
     * Set sitePlanPdf
     *
     * @param string $sitePlanPdf
     *
     * @return PropertyDetails
     */
    public function setSitePlanPdf($sitePlanPdf)
    {
        $this->sitePlanPdf = $sitePlanPdf;

        return $this;
    }

    /**
     * Get sitePlanPdf
     *
     * @return string
     */
    public function getSitePlanPdf()
    {
        return $this->sitePlanPdf;
    }

    /**
     * Set videoUrl
     *
     * @param string $videoUrl
     *
     * @return PropertyDetails
     */
    public function setVideoUrl($videoUrl)
    {
        $this->videoUrl = $videoUrl;

        return $this;
    }

    /**
     * Get videoUrl
     *
     * @return string
     */
    public function getVideoUrl()
    {
        return $this->videoUrl;
    }

    /**
     * Set heroImage
     *
     * @param string $heroImage
     *
     * @return PropertyDetails
     */
    public function setHeroImage($heroImage)
    {
        $this->heroImage = $heroImage;

        return $this;
    }

    /**
     * Get heroImage
     *
     * @return string
     */
    public function getHeroImage()
    {
        return $this->heroImage;
    }

    /**
     * Set geoAddress1
     *
     * @param string $geoAddress1
     *
     * @return PropertyDetails
     */
    public function setGeoAddress1($geoAddress1)
    {
        $this->geoAddress1 = $geoAddress1;

        return $this;
    }

    /**
     * Get geoAddress1
     *
     * @return string
     */
    public function getGeoAddress1()
    {
        return $this->geoAddress1;
    }

    /**
     * Set geoAddress2
     *
     * @param string $geoAddress2
     *
     * @return PropertyDetails
     */
    public function setGeoAddress2($geoAddress2)
    {
        $this->geoAddress2 = $geoAddress2;

        return $this;
    }

    /**
     * Get geoAddress2
     *
     * @return string
     */
    public function getGeoAddress2()
    {
        return $this->geoAddress2;
    }

    /**
     * Set geoCity
     *
     * @param string $geoCity
     *
     * @return PropertyDetails
     */
    public function setGeoCity($geoCity)
    {
        $this->geoCity = $geoCity;

        return $this;
    }

    /**
     * Get geoCity
     *
     * @return string
     */
    public function getGeoCity()
    {
        return $this->geoCity;
    }

    /**
     * Set geoProvince
     *
     * @param string $geoProvince
     *
     * @return PropertyDetails
     */
    public function setGeoProvince($geoProvince)
    {
        $this->geoProvince = $geoProvince;

        return $this;
    }

    /**
     * Get geoProvince
     *
     * @return string
     */
    public function getGeoProvince()
    {
        return $this->geoProvince;
    }

    /**
     * Set geoProvinceRegion
     *
     * @param string $geoProvinceRegion
     *
     * @return PropertyDetails
     */
    public function setGeoProvinceRegion($geoProvinceRegion)
    {
        $this->geoProvinceRegion = $geoProvinceRegion;

        return $this;
    }

    /**
     * Get geoProvinceRegion
     *
     * @return string
     */
    public function getGeoProvinceRegion()
    {
        return $this->geoProvinceRegion;
    }

    /**
     * Set geoCountry
     *
     * @param string $geoCountry
     *
     * @return PropertyDetails
     */
    public function setGeoCountry($geoCountry)
    {
        $this->geoCountry = $geoCountry;

        return $this;
    }

    /**
     * Get geoCountry
     *
     * @return string
     */
    public function getGeoCountry()
    {
        return $this->geoCountry;
    }

    /**
     * Set geoPostal
     *
     * @param string $geoPostal
     *
     * @return PropertyDetails
     */
    public function setGeoPostal($geoPostal)
    {
        $this->geoPostal = $geoPostal;

        return $this;
    }

    /**
     * Get geoPostal
     *
     * @return string
     */
    public function getGeoPostal()
    {
        return $this->geoPostal;
    }

    /**
     * Set geoIntersetion
     *
     * @param string $geoIntersetion
     *
     * @return PropertyDetails
     */
    public function setGeoIntersetion($geoIntersetion)
    {
        $this->geoIntersetion = $geoIntersetion;

        return $this;
    }

    /**
     * Get geoIntersetion
     *
     * @return string
     */
    public function getGeoIntersetion()
    {
        return $this->geoIntersetion;
    }

    /**
     * Set geoLat
     *
     * @param float $geoLat
     *
     * @return PropertyDetails
     */
    public function setGeoLat($geoLat)
    {
        $this->geoLat = $geoLat;

        return $this;
    }

    /**
     * Get geoLat
     *
     * @return float
     */
    public function getGeoLat()
    {
        return $this->geoLat;
    }

    /**
     * Set geoLng
     *
     * @param float $geoLng
     *
     * @return PropertyDetails
     */
    public function setGeoLng($geoLng)
    {
        $this->geoLng = $geoLng;

        return $this;
    }

    /**
     * Get geoLng
     *
     * @return float
     */
    public function getGeoLng()
    {
        return $this->geoLng;
    }

    /**
     * Set socialFacebook
     *
     * @param string $socialFacebook
     *
     * @return PropertyDetails
     */
    public function setSocialFacebook($socialFacebook)
    {
        $this->socialFacebook = $socialFacebook;

        return $this;
    }

    /**
     * Get socialFacebook
     *
     * @return string
     */
    public function getSocialFacebook()
    {
        return $this->socialFacebook;
    }

    /**
     * Set socialTwitter
     *
     * @param string $socialTwitter
     *
     * @return PropertyDetails
     */
    public function setSocialTwitter($socialTwitter)
    {
        $this->socialTwitter = $socialTwitter;

        return $this;
    }

    /**
     * Get socialTwitter
     *
     * @return string
     */
    public function getSocialTwitter()
    {
        return $this->socialTwitter;
    }

    /**
     * Set socialUrl
     *
     * @param string $socialUrl
     *
     * @return PropertyDetails
     */
    public function setSocialUrl($socialUrl)
    {
        $this->socialUrl = $socialUrl;

        return $this;
    }

    /**
     * Get socialUrl
     *
     * @return string
     */
    public function getSocialUrl()
    {
        return $this->socialUrl;
    }

    /**
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyDetails
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
     * Set seoTitle
     *
     * @param string $seoTitle
     *
     * @return PropertyDetails
     */
    public function setSeoTitle($seoTitle)
    {
        $this->seoTitle = $seoTitle;

        return $this;
    }

    /**
     * Get seoTitle
     *
     * @return string
     */
    public function getSeoTitle()
    {
        return $this->seoTitle;
    }

    /**
     * Set seoDescription
     *
     * @param string $seoDescription
     *
     * @return PropertyDetails
     */
    public function setSeoDescription($seoDescription)
    {
        $this->seoDescription = $seoDescription;

        return $this;
    }

    /**
     * Get seoDescription
     *
     * @return string
     */
    public function getSeoDescription()
    {
        return $this->seoDescription;
    }

    /**
     * Set seoKeywords
     *
     * @param string $seoKeywords
     *
     * @return PropertyDetails
     */
    public function setSeoKeywords($seoKeywords)
    {
        $this->seoKeywords = $seoKeywords;

        return $this;
    }

    /**
     * Get seoKeywords
     *
     * @return string
     */
    public function getSeoKeywords()
    {
        return $this->seoKeywords;
    }

    /**
     * Set seoIsIndex
     *
     * @param boolean $seoIsIndex
     *
     * @return PropertyDetails
     */
    public function setSeoIsIndex($seoIsIndex)
    {
        $this->seoIsIndex = $seoIsIndex;

        return $this;
    }

    /**
     * Get seoIsIndex
     *
     * @return boolean
     */
    public function getSeoIsIndex()
    {
        return $this->seoIsIndex;
    }

    /**
     * @return string
     */
    public function getHeroImageAlt()
    {
        return $this->heroImageAlt;
    }

    /**
     * @param string $heroImageAlt
     * @return PropertyDetails
     */
    public function setHeroImageAlt($heroImageAlt)
    {
        $this->heroImageAlt = $heroImageAlt;
        return $this;
    }



    /**
     * Copy data from object
     *
     * @param PropertyDetails $from
     *
     * @return PropertyDetails
     */
    public function copyDataFrom(PropertyDetails $from)
    {
        $this->setSqft($from->getSqft());
        $this->setVacantSqft($from->getVacantSqft());
        $this->setDescription($from->getDescription());
        $this->setLeedDescription($from->getLeedDescription());
        $this->setMarketingPdf($from->getMarketingPdf());
        $this->setSitePlanPdf($from->getSitePlanPdf());
        $this->setVideoUrl($from->getVideoUrl());
        $this->setHeroImage($from->getHeroImage());
        $this->setHeroImageAlt($from->getHeroImageAlt());
        $this->setGeoAddress1($from->getGeoAddress1());
        $this->setGeoAddress2($from->getGeoAddress2());
        $this->setGeoCity($from->getGeoCity());
        $this->setGeoProvince($from->getGeoProvince());
        $this->setGeoProvinceRegion($from->getGeoProvinceRegion());
        $this->setGeoCountry($from->getGeoCountry());
        $this->setGeoPostal($from->getGeoPostal());
        $this->setGeoIntersetion($from->getGeoIntersetion());
        $this->setGeoLat($from->getGeoLat());
        $this->setGeoLng($from->getGeoLng());
        $this->setSocialFacebook($from->getSocialFacebook());
        $this->setSocialTwitter($from->getSocialTwitter());
        $this->setSocialUrl($from->getSocialUrl());
        $this->setSeoTitle($from->getSeoTitle());
        $this->setSeoDescription($from->getSeoDescription());
        $this->setSeoKeywords($from->getSeoKeywords());
        $this->setSeoIsIndex($from->getSeoIsIndex());

        return $this;
    }
}
