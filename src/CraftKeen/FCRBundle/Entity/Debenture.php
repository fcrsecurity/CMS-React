<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Debenture
 *
 * @ORM\Table(name="investors_debenture")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\DebentureRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Debenture extends BaseEntity
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
     * @var string
     *
     * @ORM\Column(name="series", type="string", length=5, nullable=false)
     * @Gedmo\Versioned
     */
    private $series;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="maturity_date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $maturityDate;

    /**
     * @var string
     *
     * @ORM\Column(name="principal_outstanding", type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $principalOutstanding;

    /**
     * @var string
     *
     * @ORM\Column(name="cupon", type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $cupon;

    /**
     * @var string
     *
     * @ORM\Column(name="interest_payment_date", type="string", length=50, nullable=true)
     * @Gedmo\Versioned
     */
    private $interestPaymentDate;

    /**
     * @var Debenture
     *
     * One Debenture have One Copy of Debenture.
     * @ORM\OneToOne(targetEntity="Debenture", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * @var Debenture
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="Debenture", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * Get Series of Debenture
     *
     * @return string
     */
    public function __toString()
    {
        return $this->series;
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
     * Set series
     *
     * @param string $series
     *
     * @return Debenture
     */
    public function setSeries($series)
    {
        $this->series = $series;

        return $this;
    }

    /**
     * Get series
     *
     * @return string
     */
    public function getSeries()
    {
        return $this->series;
    }

    /**
     * Set maturityDate
     *
     * @param \DateTime $maturityDate
     *
     * @return Debenture
     */
    public function setMaturityDate($maturityDate)
    {
        $this->maturityDate = $maturityDate;

        return $this;
    }

    /**
     * Get maturityDate
     *
     * @return \DateTime
     */
    public function getMaturityDate()
    {
        return $this->maturityDate;
    }

    /**
     * Set principalOutstanding
     *
     * @param string $principalOutstanding
     *
     * @return Debenture
     */
    public function setPrincipalOutstanding($principalOutstanding)
    {
        $this->principalOutstanding = $principalOutstanding;

        return $this;
    }

    /**
     * Get principalOutstanding
     *
     * @return string
     */
    public function getPrincipalOutstanding()
    {
        return $this->principalOutstanding;
    }

    /**
     * Set cupon
     *
     * @param string $cupon
     *
     * @return Debenture
     */
    public function setCupon($cupon)
    {
        $this->cupon = $cupon;

        return $this;
    }

    /**
     * Get cupon
     *
     * @return string
     */
    public function getCupon()
    {
        return $this->cupon;
    }

    /**
     * Set interestPaymentDate
     *
     * @param string $interestPaymentDate
     *
     * @return Debenture
     */
    public function setInterestPaymentDate($interestPaymentDate)
    {
        $this->interestPaymentDate = $interestPaymentDate;

        return $this;
    }

    /**
     * Get interestPaymentDate
     *
     * @return string
     */
    public function getInterestPaymentDate()
    {
        return $this->interestPaymentDate;
    }


    /**
     * Set copyOf
     *
     * @param Debenture $copyOf
     *
     * @return Debenture
     */
    public function setCopyOf(Debenture $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Debenture
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * Set langParent
     *
     * @param Debenture $langParent
     *
     * @return Debenture
     */
    public function setLangParent(Debenture $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Debenture
     */
    public function getLangParent()
    {
        return $this->langParent;
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
            'series',
            'principalOutstanding',
            'cupon',
            'interestPaymentDate',
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
        return 'admin_investors_debenture_';
    }
}
