<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PropertyVacancy
 *
 * @ORM\Table(name="leasing_property_vacancy")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyVacancyRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PropertyVacancy
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
     * Many Vacancies has One Property.
     * @ORM\ManyToOne(targetEntity="Property", inversedBy="vacancyList")
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var float
     *
     * @ORM\Column(name="vacant_sqft", type="float")
     * @Gedmo\Versioned
     */
    private $vacantSqft;

    /**
     * PropertyVacancy clone
     */
    public function __clone()
    {
        $this->id = null;
    }
    
    /**
     * Get vacantSqft
     *
     * @return string
     */
    public function __toString()
    {
        return $this->vacantSqft;
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
     * Set vacantSqft
     *
     * @param float $vacantSqft
     *
     * @return PropertyVacancy
     */
    public function setVacantSqft($vacantSqft)
    {
        $this->vacantSqft = $vacantSqft;

        return $this;
    }

    /**
     * Get vacantSqft
     *
     * @return float
     */
    public function getVacantSqft()
    {
        return $this->vacantSqft;
    }

    /**
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyVacancy
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
}
