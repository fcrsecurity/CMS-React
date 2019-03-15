<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * HistoricalData
 *
 * @ORM\Table(name="historical_data")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\HistoricalDataRepository")
 */
class HistoricalData
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
     * @ORM\Column(name="date", type="date", nullable=true)
     */
    private $date;

    /**
     * @var float
     *
     * @ORM\Column(name="open", type="float", nullable=true)
     */
    private $open;

    /**
     * @var float
     *
     * @ORM\Column(name="high", type="float", nullable=true)
     */
    private $high;

    /**
     * @var float
     *
     * @ORM\Column(name="low", type="float", nullable=true)
     */
    private $low;

    /**
     * @var float
     *
     * @ORM\Column(name="close", type="float", nullable=true)
     */
    private $close;

    /**
     * @var float
     *
     * @ORM\Column(name="adj_close", type="float", nullable=true)
     */
    private $adjClose;

    /**
     * @var float
     *
     * @ORM\Column(name="volume", type="string", nullable=true)
     */
    private $volume;


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
     * @return HistoricalData
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
     * Set open
     *
     * @param float $open
     *
     * @return HistoricalData
     */
    public function setOpen($open)
    {
        $this->open = $open;

        return $this;
    }

    /**
     * Get open
     *
     * @return float
     */
    public function getOpen()
    {
        return $this->open;
    }

    /**
     * Set high
     *
     * @param float $high
     *
     * @return HistoricalData
     */
    public function setHigh($high)
    {
        $this->high = $high;

        return $this;
    }

    /**
     * Get high
     *
     * @return float
     */
    public function getHigh()
    {
        return $this->high;
    }

    /**
     * Set low
     *
     * @param float $low
     *
     * @return HistoricalData
     */
    public function setLow($low)
    {
        $this->low = $low;

        return $this;
    }

    /**
     * Get low
     *
     * @return float
     */
    public function getLow()
    {
        return $this->low;
    }

    /**
     * Set close
     *
     * @param float $close
     *
     * @return HistoricalData
     */
    public function setClose($close)
    {
        $this->close = $close;

        return $this;
    }

    /**
     * Get close
     *
     * @return float
     */
    public function getClose()
    {
        return $this->close;
    }

    /**
     * Set adjClose
     *
     * @param float $adjClose
     *
     * @return HistoricalData
     */
    public function setAdjClose($adjClose)
    {
        $this->adjClose = $adjClose;

        return $this;
    }

    /**
     * Get adjClose
     *
     * @return float
     */
    public function getAdjClose()
    {
        return $this->adjClose;
    }

    /**
     * Set volume
     *
     * @param float $volume
     *
     * @return HistoricalData
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return float
     */
    public function getVolume()
    {
        return $this->volume;
    }
}

