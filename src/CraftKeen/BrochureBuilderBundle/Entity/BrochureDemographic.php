<?php

namespace CraftKeen\BrochureBuilderBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use CraftKeen\FCRBundle\Entity\PropertyDemographic;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * BrochureDemographic
 *
 * @ORM\Table(name="leasing_brochure_demographic")
 * @ORM\Entity(repositoryClass="CraftKeen\BrochureBuilderBundle\Repository\BrochureDemographicRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class BrochureDemographic
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
     * @ORM\OneToOne(targetEntity="Brochure", inversedBy="demographic", cascade={"persist"})
     * @ORM\JoinColumn(name="brochure_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $brochure;

    /**
     * @ORM\OneToOne(targetEntity="BrochureImage", cascade={"persist"}, orphanRemoval=true)
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     * @var float
     *
     * @ORM\Column(name="annual_average_daily_traffic", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $annualAverageDailyTraffic;

    /**
     * @var float
     *
     * @ORM\Column(name="population_1km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $population1km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_1km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $household1km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_income_1km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $householdIncome1km;

    /**
     * @var float
     *
     * @ORM\Column(name="population_3km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $population3km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_3km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $household3km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_income_3km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $householdIncome3km;

    /**
     * @var float
     *
     * @ORM\Column(name="population_5km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $population5km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_5km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $household5km;

    /**
     * @var float
     *
     * @ORM\Column(name="household_income_5km", type="float", nullable=true)
     * @Assert\Type("float")
     */
    private $householdIncome5km;


    /**
     * Copy data from object
     *
     * @param PropertyDemographic $from
     *
     * @return BrochureAerial
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

    /**
     * @return mixed
     */
    private function mergeValue($prev, $new)
    {
        return (null === $prev || 0 === $prev) && 0 < $new ? $new : $prev;
    }

    /**
     * Merge data from object
     *
     * @param PropertyDemographic $from
     *
     * @return BrochureAerial
     */
    public function mergeDataFrom(PropertyDemographic $from)
    {
        $this->setAnnualAverageDailyTraffic(
            $this->mergeValue($this->getAnnualAverageDailyTraffic(), $from->getAnnualAverageDailyTraffic())
        );

        $this->setPopulation1km(
            $this->mergeValue($this->getPopulation1km(), $from->getPopulation1km())
        );

        $this->setHousehold1km(
            $this->mergeValue($this->getHousehold1km(), $from->getHousehold1km())
        );

        $this->setHouseholdIncome1km(
            $this->mergeValue($this->getHouseholdIncome1km(), $from->getHouseholdIncome1km())
        );

        $this->setPopulation3km(
            $this->mergeValue($this->getPopulation3km(), $from->getPopulation3km())
        );

        $this->setHousehold3km(
            $this->mergeValue($this->getHousehold3km(), $from->getHousehold3km())
        );

        $this->setHouseholdIncome3km(
            $this->mergeValue($this->getHouseholdIncome3km(), $from->getHouseholdIncome3km())
        );

        $this->setPopulation5km(
            $this->mergeValue($this->getPopulation5km(), $from->getPopulation5km())
        );

        $this->setHousehold5km(
            $this->mergeValue($this->getHousehold5km(), $from->getHousehold5km())
        );

        $this->setHouseholdIncome5km(
            $this->mergeValue($this->getHouseholdIncome5km(), $from->getHouseholdIncome5km())
        );

        return $this;
    }

    /**
     * @return array
     */
    public function toJson()
    {
        return [
            'image' => $this->getImage() ? $this->getImage()->toJson() : null,
            'stats' => [
                'trafficCount' => $this->getAnnualAverageDailyTraffic() ?: 0,
                'population' => [
                    'km1' => $this->getPopulation1km() ?: 0,
                    'km3' => $this->getPopulation3km() ?: 0,
                    'km5' => $this->getPopulation5km() ?: 0,
                ],
                'totalHouseholds' => [
                    'km1' => $this->getHousehold1km() ?: 0,
                    'km3' => $this->getHousehold3km() ?: 0,
                    'km5' => $this->getHousehold5km() ?: 0,
                ],
                'avgHouseholdIncome' => [
                    'km1' => $this->getHouseholdIncome1km() ?: 0,
                    'km3' => $this->getHouseholdIncome3km() ?: 0,
                    'km5' => $this->getHouseholdIncome5km() ?: 0,
                ]
            ]
        ];
    }

    /**
     * Get brochure
     *
     * @return int
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * @return BrochureDemographic
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
     * Set brochure
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\Brochure $brochure
     *
     * @return BrochureDemographic
     */
    public function setBrochure(\CraftKeen\BrochureBuilderBundle\Entity\Brochure $brochure = null)
    {
        $this->brochure = $brochure;

        return $this;
    }

    /**
     * Get brochure
     *
     * @return \CraftKeen\BrochureBuilderBundle\Entity\Brochure
     */
    public function getBrochure()
    {
        return $this->brochure;
    }

    /**
     * Set image
     *
     * @param \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $image
     *
     * @return BrochureDemographic
     */
    public function setImage(\CraftKeen\BrochureBuilderBundle\Entity\BrochureImage $image = null)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return \CraftKeen\BrochureBuilderBundle\Entity\BrochureImage
     */
    public function getImage()
    {
        return $this->image;
    }
}
