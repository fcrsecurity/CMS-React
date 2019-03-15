<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * AnalystCoverage
 *
 * @ORM\Table(name="investors_analyst_coverage")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\AnalystCoverageRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class AnalystCoverage extends BaseEntity
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
     * @ORM\Column(name="title", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(name="person", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $person;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=20)
     * @Gedmo\Versioned
     */
    private $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     * @Gedmo\Versioned
     */
    private $type;

    /**
     * @var AnalystCoverage
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="AnalystCoverage", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var AnalystCoverage
     *
     * One AnalystCoverage have One Copy of AnalystCoverage.
     * @ORM\OneToOne(targetEntity="AnalystCoverage", cascade={"persist"})
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
        return $this->title;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return AnalystCoverage
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
     * Set title
     *
     * @param string $title
     *
     * @return AnalystCoverage
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
     * Set person
     *
     * @param string $person
     *
     * @return AnalystCoverage
     */
    public function setPerson($person)
    {
        $this->person = $person;

        return $this;
    }

    /**
     * Get person
     *
     * @return string
     */
    public function getPerson()
    {
        return $this->person;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return AnalystCoverage
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set type
     *
     * @param string $type
     *
     * @return AnalystCoverage
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set langParent
     *
     * @param AnalystCoverage $langParent
     *
     * @return AnalystCoverage
     */
    public function setLangParent(AnalystCoverage $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return AnalystCoverage
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param AnalystCoverage $copyOf
     *
     * @return AnalystCoverage
     */
    public function setCopyOf(AnalystCoverage $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return AnalystCoverage
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
            'title',
            'person',
            'phone',
            'type',
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
     * Returns Entity Base Route
     *
     * @return string
     */
    public function getEntityBaseRoute()
    {
        return 'admin_investors_analyst-coverage_';
    }
}
