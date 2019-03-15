<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Dividend
 *
 * @ORM\Table(name="investors_dividend")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\DividendRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Dividend extends BaseEntity
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
     * @ORM\Column(name="declared_date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $declaredDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="ex_dividend_date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $exDividendDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="record_date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $recordDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payable_date", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $payableDate;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="special_dividend_in_kind", type="date", nullable=true)
     * @Gedmo\Versioned
     */
    private $specialDividendInKind;

    /**
     * @var float
     *
     * @ORM\Column(name="dividend_amount", type="float", nullable=true)
     * @Gedmo\Versioned
     */
    private $dividendAmount;

    /**
     * @var Dividend
     *
     * One Dividend have One Copy of Dividend.
     * @ORM\OneToOne(targetEntity="Dividend", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * @var Dividend
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="Dividend", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * Get Full Name of Conference Call event
     *
     * @return string
     */
    public function __toString()
    {
        return $this->declaredDate->format('d-m-Y');
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
     * Set declaredDate
     *
     * @param \DateTime $declaredDate
     *
     * @return Dividend
     */
    public function setDeclaredDate($declaredDate)
    {
        $this->declaredDate = $declaredDate;

        return $this;
    }

    /**
     * Get declaredDate
     *
     * @return \DateTime
     */
    public function getDeclaredDate()
    {
        return $this->declaredDate;
    }

    /**
     * Set exDividendDate
     *
     * @param \DateTime $exDividendDate
     *
     * @return Dividend
     */
    public function setExDividendDate($exDividendDate)
    {
        $this->exDividendDate = $exDividendDate;

        return $this;
    }

    /**
     * Get exDividendDate
     *
     * @return \DateTime
     */
    public function getExDividendDate()
    {
        return $this->exDividendDate;
    }

    /**
     * Set recordDate
     *
     * @param \DateTime $recordDate
     *
     * @return Dividend
     */
    public function setRecordDate($recordDate)
    {
        $this->recordDate = $recordDate;

        return $this;
    }

    /**
     * Get recordDate
     *
     * @return \DateTime
     */
    public function getRecordDate()
    {
        return $this->recordDate;
    }

    /**
     * Set payableDate
     *
     * @param \DateTime $payableDate
     *
     * @return Dividend
     */
    public function setPayableDate($payableDate)
    {
        $this->payableDate = $payableDate;

        return $this;
    }

    /**
     * Get payableDate
     *
     * @return \DateTime
     */
    public function getPayableDate()
    {
        return $this->payableDate;
    }

    /**
     * Set specialDividendInKind
     *
     * @param \DateTime $specialDividendInKind
     *
     * @return Dividend
     */
    public function setSpecialDividendInKind($specialDividendInKind)
    {
        $this->specialDividendInKind = $specialDividendInKind;

        return $this;
    }

    /**
     * Get specialDividendInKind
     *
     * @return \DateTime
     */
    public function getSpecialDividendInKind()
    {
        return $this->specialDividendInKind;
    }

    /**
     * Set dividendAmount
     *
     * @param float $dividendAmount
     *
     * @return Dividend
     */
    public function setDividendAmount($dividendAmount)
    {
        $this->dividendAmount = $dividendAmount;

        return $this;
    }

    /**
     * Get dividendAmount
     *
     * @return float
     */
    public function getDividendAmount()
    {
        return $this->dividendAmount;
    }


    /**
     * Set copyOf
     *
     * @param Dividend $copyOf
     *
     * @return Dividend
     */
    public function setCopyOf(Dividend $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Dividend
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * Set langParent
     *
     * @param Dividend $langParent
     *
     * @return Dividend
     */
    public function setLangParent(Dividend $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Dividend
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
            'declaredDate',
            'exDividendDate',
            'recordDate',
            'payableDate',
            'specialDividendInKind',
            'dividendAmount',
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
        return 'admin_investors_dividend_';
    }
}
