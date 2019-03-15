<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricalDataDividend
 *
 * @ORM\Table(name="historical_data_dividend")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\HistoricalDataDividendRepository")
 */
class HistoricalDataDividend
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
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="dividend", type="float")
     */
    private $dividend;


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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return HistoricalDataDividend
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
     * Set dividend
     *
     * @param float $dividend
     *
     * @return HistoricalDataDividend
     */
    public function setDividend($dividend)
    {
        $this->dividend = $dividend;

        return $this;
    }

    /**
     * Get dividend
     *
     * @return float
     */
    public function getDividend()
    {
        return $this->dividend;
    }
}

