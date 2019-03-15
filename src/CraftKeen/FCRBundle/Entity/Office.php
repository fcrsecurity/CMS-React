<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CraftKeen\CMS\PageBundle\Entity\VersionableInterface;
use CraftKeen\CMS\PageBundle\Entity\TranslatableInterface;
use CraftKeen\CMS\PageBundle\Entity\SortableInterface;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Office
 *
 * @ORM\Table(name="office")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\OfficeRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Office extends BaseEntity implements VersionableInterface, TranslatableInterface, SortableInterface
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
     * @ORM\Column(name="header", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $header;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $address;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $city;

    /**
     * @var string
     *
     * @ORM\Column(name="Province", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $province;

    /**
     * @var string
     *
     * @ORM\Column(name="postal", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $postal;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $tel;

    /**
     * @var string
     *
     * @ORM\Column(name="tollfree", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $tollfree;

    /**
     * @var string
     *
     * @ORM\Column(name="fax", type="string", length=255, nullable=true)
     * @Gedmo\Versioned
     */
    private $fax;

    /**
     * @var bool
     *
     * @ORM\Column(name="is_main", type="boolean")
     * @Gedmo\Versioned
     */
    private $isMain;

    /**
     * @var Office
     *
     * Many Pages have One Parent.
     * @ORM\ManyToOne(targetEntity="Office", cascade={"detach"})
     * @ORM\JoinColumn(nullable=true)
     */
    protected $langParent;

    /**
     * @var Office
     *
     * One Office have One Copy of Office.
     * @ORM\OneToOne(targetEntity="Office", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Name of the office
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
     * @return Office
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
     * Set header
     *
     * @param string $header
     *
     * @return Office
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
     * Set address
     *
     * @param string $address
     *
     * @return Office
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Office
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
     * Set province
     *
     * @param string $province
     *
     * @return Office
     */
    public function setProvince($province)
    {
        $this->province = $province;

        return $this;
    }

    /**
     * Get province
     *
     * @return string
     */
    public function getProvince()
    {
        return $this->province;
    }

    /**
     * Set postal
     *
     * @param string $postal
     *
     * @return Office
     */
    public function setPostal($postal)
    {
        $this->postal = $postal;

        return $this;
    }

    /**
     * Get postal
     *
     * @return string
     */
    public function getPostal()
    {
        return $this->postal;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Office
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set tollfree
     *
     * @param string $tollfree
     *
     * @return Office
     */
    public function setTollfree($tollfree)
    {
        $this->tollfree = $tollfree;

        return $this;
    }

    /**
     * Get tollfree
     *
     * @return string
     */
    public function getTollfree()
    {
        return $this->tollfree;
    }

    /**
     * Set fax
     *
     * @param string $fax
     *
     * @return Office
     */
    public function setFax($fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get fax
     *
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set isMain
     *
     * @param boolean $isMain
     *
     * @return Office
     */
    public function setIsMain($isMain)
    {
        $this->isMain = $isMain;

        return $this;
    }

    /**
     * Get isMain
     *
     * @return bool
     */
    public function getIsMain()
    {
        return $this->isMain;
    }

    /**
     * Set langParent
     *
     * @param Office $langParent
     *
     * @return Office
     */
    public function setLangParent(Office $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Office
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param Office $copyOf
     *
     * @return Office
     */
    public function setCopyOf(Office $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Office
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
            'header',
            'address',
            'city',
            'province',
            'postal',
            'tel',
            'tollFree',
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
        return 'admin_about_office_';
    }
}
