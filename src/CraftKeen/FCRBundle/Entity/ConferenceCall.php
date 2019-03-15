<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * InvestorConferenceCall
 *
 * @ORM\Table(name="investor_conference_call")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\ConferenceCallRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class ConferenceCall extends BaseEntity
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
     * @ORM\Column(name="category", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

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
     * @ORM\Column(name="listen_link", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $listenLink;

    /**
     * @var string
     *
     * @ORM\Column(name="slides_link", type="text", nullable=true)
     * @Gedmo\Versioned
     */
    private $slidesLink;

    /**
     * Many ConferenceCalls have One PressRelease.
     *
     * @ORM\ManyToOne(targetEntity="PressRelease", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $pressRelease;

    /**
     * Many ConferenceCalls have One Financial Report.
     *
     * @ORM\ManyToOne(targetEntity="FinancialReport", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, referencedColumnName="id")
     * @Gedmo\Versioned
     */
    private $quarterlyReport;

    /**
     * @var string
     *
     * @ORM\Column(name="financial_report_file_name",
     *     type="string",
     *     nullable=true,
     *     length=10,
     *     options={"default" : "q1"
     * })
     * @Gedmo\Versioned
     */
    private $quarterlyReportFileName;

    /**
     * @var ConferenceCall
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="ConferenceCall", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var ConferenceCall
     *
     * One ConferenceCall have One Copy of ConferenceCall.
     * @ORM\OneToOne(targetEntity="ConferenceCall", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Full Name of Conference Call event
     *
     * @return string
     */
    public function __toString()
    {
        return $this->category . ' ' . $this->title;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return ConferenceCall
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
     * Set category
     *
     * @param string $category
     *
     * @return ConferenceCall
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return ConferenceCall
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return ConferenceCall
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
     * Set listenLink
     *
     * @param string $listenLink
     *
     * @return ConferenceCall
     */
    public function setListenLink($listenLink)
    {
        $this->listenLink = $listenLink;

        return $this;
    }

    /**
     * Get listenLink
     *
     * @return string
     */
    public function getListenLink()
    {
        return $this->listenLink;
    }

    /**
     * Set slidesLink
     *
     * @param string $slidesLink
     *
     * @return ConferenceCall
     */
    public function setSlidesLink($slidesLink)
    {
        $this->slidesLink = $slidesLink;

        return $this;
    }

    /**
     * Get slidesLink
     *
     * @return string
     */
    public function getSlidesLink()
    {
        return $this->slidesLink;
    }

    /**
     * Set langParent
     *
     * @param ConferenceCall $langParent
     *
     * @return ConferenceCall
     */
    public function setLangParent(ConferenceCall $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return ConferenceCall
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set quarterlyReportFileName
     *
     * @param string $quarterlyReportFileName
     *
     * @return ConferenceCall
     */
    public function setQuarterlyReportFileName($quarterlyReportFileName)
    {
        $this->quarterlyReportFileName = $quarterlyReportFileName;

        return $this;
    }

    /**
     * Get quarterlyReportFileName
     *
     * @return string
     */
    public function getQuarterlyReportFileName()
    {
        return $this->quarterlyReportFileName;
    }

    /**
     * Set pressRelease
     *
     * @param PressRelease $pressRelease
     *
     * @return ConferenceCall
     */
    public function setPressRelease(PressRelease $pressRelease = null)
    {
        $this->pressRelease = $pressRelease;

        return $this;
    }

    /**
     * Get pressRelease
     *
     * @return PressRelease
     */
    public function getPressRelease()
    {
        return $this->pressRelease;
    }

    /**
     * Set quarterlyReport
     *
     * @param FinancialReport $quarterlyReport
     *
     * @return ConferenceCall
     */
    public function setQuarterlyReport(FinancialReport $quarterlyReport = null)
    {
        $this->quarterlyReport = $quarterlyReport;

        return $this;
    }

    /**
     * Get quarterlyReport
     *
     * @return FinancialReport
     */
    public function getQuarterlyReport()
    {
        return $this->quarterlyReport;
    }

    /**
     * Set copyOf
     *
     * @param ConferenceCall $copyOf
     *
     * @return ConferenceCall
     */
    public function setCopyOf(ConferenceCall $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return ConferenceCall
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
            'category',
            'title',
            'date',
            'listenLink',
            'slidesLink',
            'pressRelease',
            'quarterLyreport',
            'quarterLyreportFilename',
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
        return 'admin_investors_conference-call_';
    }
}
