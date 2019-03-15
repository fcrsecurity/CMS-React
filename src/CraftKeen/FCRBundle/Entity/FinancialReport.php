<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FinancialReport
 *
 * @ORM\Table(name="investors_financial_report")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\FinancialReportRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class FinancialReport extends BaseEntity
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
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     * @Gedmo\Versioned
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="Q1", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $q1;

    /**
     * @var string
     *
     * @ORM\Column(name="Q2", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $q2;

    /**
     * @var string
     *
     * @ORM\Column(name="Q3", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $q3;

    /**
     * @var string
     *
     * @ORM\Column(name="Q4", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $q4;

    /**
     * @var string
     *
     * @ORM\Column(name="annual", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $annual;

    /**
     * @var FinancialReport
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="FinancialReport", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var FinancialReport
     *
     * One FinancialReport have One Copy of FinancialReport.
     * @ORM\OneToOne(targetEntity="FinancialReport", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get childName
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->year;
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
     * Set q1
     *
     * @param string $q1
     *
     * @return FinancialReport
     */
    public function setQ1($q1)
    {
        $this->q1 = $q1;

        return $this;
    }

    /**
     * Get q1
     *
     * @return string
     */
    public function getQ1()
    {
        return $this->q1;
    }

    /**
     * Set q2
     *
     * @param string $q2
     *
     * @return FinancialReport
     */
    public function setQ2($q2)
    {
        $this->q2 = $q2;

        return $this;
    }

    /**
     * Get q2
     *
     * @return string
     */
    public function getQ2()
    {
        return $this->q2;
    }

    /**
     * Set q3
     *
     * @param string $q3
     *
     * @return FinancialReport
     */
    public function setQ3($q3)
    {
        $this->q3 = $q3;

        return $this;
    }

    /**
     * Get q3
     *
     * @return string
     */
    public function getQ3()
    {
        return $this->q3;
    }

    /**
     * Set q4
     *
     * @param string $q4
     *
     * @return FinancialReport
     */
    public function setQ4($q4)
    {
        $this->q4 = $q4;

        return $this;
    }

    /**
     * Get q4
     *
     * @return string
     */
    public function getQ4()
    {
        return $this->q4;
    }

    /**
     * Set annual
     *
     * @param string $annual
     *
     * @return FinancialReport
     */
    public function setAnnual($annual)
    {
        $this->annual = $annual;

        return $this;
    }

    /**
     * Get annual
     *
     * @return string
     */
    public function getAnnual()
    {
        return $this->annual;
    }

    /**
     * Set langParent
     *
     * @param FinancialReport $langParent
     *
     * @return FinancialReport
     */
    public function setLangParent(FinancialReport $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return FinancialReport
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set year
     *
     * @param integer $year
     *
     * @return FinancialReport
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return integer
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set copyOf
     *
     * @param FinancialReport $copyOf
     *
     * @return FinancialReport
     */
    public function setCopyOf(FinancialReport $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return FinancialReport
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
            'year',
            'q1',
            'q2',
            'q3',
            'q4',
            'annual',
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
        return 'admin_investors_financial-report_';
    }
}
