<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * Sustainability
 *
 * @ORM\Table(name="sustainability")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\SustainabilityRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class Sustainability extends BaseEntity
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
     * @var int
     *
     * @ORM\Column(name="year", type="integer")
     * @Gedmo\Versioned
     */
    private $year;

    /**
     * @var string
     *
     * @ORM\Column(name="link", type="text")
     * @Gedmo\Versioned
     */
    private $link;

    /**
     * @var Sustainability
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="Sustainability", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var Sustainability
     *
     * One Sustainability have One Copy of Sustainability.
     * @ORM\OneToOne(targetEntity="Sustainability", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;


    /**
     * Get Full Name of Manager
     *
     * @return string
     */
    public function __toString()
    {
        return (string)$this->year;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return Sustainability
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
     * Set year
     *
     * @param integer $year
     *
     * @return Sustainability
     */
    public function setYear($year)
    {
        $this->year = $year;

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set link
     *
     * @param string $link
     *
     * @return Sustainability
     */
    public function setLink($link)
    {
        $this->link = $link;

        return $this;
    }

    /**
     * Get link
     *
     * @return string
     */
    public function getLink()
    {
        return $this->link;
    }

    /**
     * Set langParent
     *
     * @param Sustainability $langParent
     *
     * @return Sustainability
     */
    public function setLangParent(Sustainability $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return Sustainability
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param Sustainability $copyOf
     *
     * @return Sustainability
     */
    public function setCopyOf(Sustainability $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return Sustainability
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
            'link',
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
        return 'admin_community_sustainability-reports_';
    }
}
