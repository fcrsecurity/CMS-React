<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PropertyDemographic
 *
 * @ORM\Table(name="leasing_property_demographic")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\PropertyDemographicRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class PropertyDemographic
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
     * @ORM\OneToOne(targetEntity="Property", inversedBy="demographic", cascade={"persist"})
     * @ORM\JoinColumn(name="property_id", referencedColumnName="id", onDelete="CASCADE")
     * @Gedmo\Versioned
     */
    private $property;

    /**
     * @var float
     *
     * @ORM\Column(name="annual_average_daily_traffic", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $annualAverageDailyTraffic;

    /**
     * @var float
     *
     * @ORM\Column(name="population_1km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $population1km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_1km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $household1km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_income_1km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $householdIncome1km;

    /**
     * @var float
     *
     * @ORM\Column(name="population_3km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $population3km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_3km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $household3km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_income_3km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $householdIncome3km;

    /**
     * @var float
     *
     * @ORM\Column(name="population_5km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $population5km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_5km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $household5km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_income_5km", type="float", nullable=true)
     * @Assert\Type("float")
     * @Gedmo\Versioned
     */
    private $householdIncome5km;

    /**
     * Set Id
     *
     * @param integer $id
     *
     * @return PropertyDemographic
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
     * Set annualAverageDailyTraffic
     *
     * @param float $annualAverageDailyTraffic
     *
     * @return PropertyDemographic
     */
    public function setAnnualAverageDailyTraffic($annualAverageDailyTraffic)
    {
        $this->annualAverageDailyTraffic = $annualAverageDailyTraffic;

        return $this;
    }

    /**
     * Get annualAverageDailyTraffic
     *
     * @return float
     */
    public function getAnnualAverageDailyTraffic()
    {
        return $this->annualAverageDailyTraffic;
    }

    /**
     * Set population1km
     *
     * @param float $population1km
     *
     * @return PropertyDemographic
     */
    public function setPopulation1km($population1km)
    {
        $this->population1km = $population1km;

        return $this;
    }

    /**
     * Get population1km
     *
     * @return float
     */
    public function getPopulation1km()
    {
        return $this->population1km;
    }

    /**
     * Set household1km
     *
     * @param float $household1km
     *
     * @return PropertyDemographic
     */
    public function setHousehold1km($household1km)
    {
        $this->household1km = $household1km;

        return $this;
    }

    /**
     * Get household1km
     *
     * @return float
     */
    public function getHousehold1km()
    {
        return $this->household1km;
    }

    /**
     * Set householdIncome1km
     *
     * @param float $householdIncome1km
     *
     * @return PropertyDemographic
     */
    public function setHouseholdIncome1km($householdIncome1km)
    {
        $this->householdIncome1km = $householdIncome1km;

        return $this;
    }

    /**
     * Get householdIncome1km
     *
     * @return float
     */
    public function getHouseholdIncome1km()
    {
        return $this->householdIncome1km;
    }

    /**
     * Set population3km
     *
     * @param float $population3km
     *
     * @return PropertyDemographic
     */
    public function setPopulation3km($population3km)
    {
        $this->population3km = $population3km;

        return $this;
    }

    /**
     * Get population3km
     *
     * @return float
     */
    public function getPopulation3km()
    {
        return $this->population3km;
    }

    /**
     * Set household3km
     *
     * @param float $household3km
     *
     * @return PropertyDemographic
     */
    public function setHousehold3km($household3km)
    {
        $this->household3km = $household3km;

        return $this;
    }

    /**
     * Get household3km
     *
     * @return float
     */
    public function getHousehold3km()
    {
        return $this->household3km;
    }

    /**
     * Set householdIncome3km
     *
     * @param float $householdIncome3km
     *
     * @return PropertyDemographic
     */
    public function setHouseholdIncome3km($householdIncome3km)
    {
        $this->householdIncome3km = $householdIncome3km;

        return $this;
    }

    /**
     * Get householdIncome3km
     *
     * @return float
     */
    public function getHouseholdIncome3km()
    {
        return $this->householdIncome3km;
    }

    /**
     * Set population5km
     *
     * @param float $population5km
     *
     * @return PropertyDemographic
     */
    public function setPopulation5km($population5km)
    {
        $this->population5km = $population5km;

        return $this;
    }

    /**
     * Get population5km
     *
     * @return float
     */
    public function getPopulation5km()
    {
        return $this->population5km;
    }

    /**
     * Set household5km
     *
     * @param float $household5km
     *
     * @return PropertyDemographic
     */
    public function setHousehold5km($household5km)
    {
        $this->household5km = $household5km;

        return $this;
    }

    /**
     * Get household5km
     *
     * @return float
     */
    public function getHousehold5km()
    {
        return $this->household5km;
    }

    /**
     * Set householdIncome5km
     *
     * @param float $householdIncome5km
     *
     * @return PropertyDemographic
     */
    public function setHouseholdIncome5km($householdIncome5km)
    {
        $this->householdIncome5km = $householdIncome5km;

        return $this;
    }

    /**
     * Get householdIncome5km
     *
     * @return float
     */
    public function getHouseholdIncome5km()
    {
        return $this->householdIncome5km;
    }

    /**
     * Set property
     *
     * @param Property $property
     *
     * @return PropertyDemographic
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
     * Copy data from object
     *
     * @param PropertyDemographic $from
     *
     * @return PropertyDemographic
     */
    public function copyDataFrom(PropertyDemographic $from)
    {
        $this->setAnnualAverageDailyTraffic($from->getAnnualAverageDailyTraffic());
        $this->setPopulation1km($from->getPopulation1km());
        $this->setHousehold1km($from->getHousehold1km());
        $this->setHouseholdIncome1km($from->getHouseholdIncome1km());
        $this->setPopulation3km($from->getPopulation3km());
        $this->setHousehold3km($from->getHousehold3km());
        $this->setHouseholdIncome3km($from->getHouseholdIncome3km());
        $this->setPopulation5km($from->getPopulation5km());
        $this->setHousehold5km($from->getHousehold5km());
        $this->setHouseholdIncome5km($from->getHouseholdIncome5km());

        return $this;
    }
}
