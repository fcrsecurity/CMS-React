<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LeasingStockValue
 *
 * @ORM\Table(name="investors_stock_value")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\LeasingStockValueRepository")
 * @ORM\HasLifecycleCallbacks
 */
class LeasingStockValue
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
     * @var string
     *
     * @ORM\Column(name="last_price", type="string", length=50, nullable=true)
     */
    private $lastPrice;

    /**
     * @var string
     *
     * @ORM\Column(name="last_trade", type="datetime", nullable=true)
     */
    private $lastTrade;

    /**
     * @var string
     *
     * @ORM\Column(name="change_value", type="string", length=50, nullable=true)
     */
    private $changeValue;

    /**
     * @var string
     *
     * @ORM\Column(name="dividend", type="string", length=50, nullable=true)
     */
    private $dividend;

    /**
     * @var string
     *
     * @ORM\Column(name="volume", type="string", length=50, nullable=true)
     */
    private $volume;

    /**
     * @var string
     *
     * @ORM\Column(name="year_high", type="string", length=50, nullable=true)
     */
    private $yearHigh;

    /**
     * @var string
     *
     * @ORM\Column(name="year_low", type="string", length=50, nullable=true)
     */
    private $yearLow;

    /**
     * @var string
     *
     * @ORM\Column(name="day_high", type="string", length=50, nullable=true)
     */
    private $dayHigh;

    /**
     * @var string
     *
     * @ORM\Column(name="day_low", type="string", length=50, nullable=true)
     */
    private $dayLow;

    /**
     * @var string
     *
     * @ORM\Column(name="open_price", type="string", length=50, nullable=true)
     */
    private $openPrice;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;


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
     * Set lastPrice
     *
     * @param string $lastPrice
     *
     * @return LeasingStockValue
     */
    public function setLastPrice($lastPrice)
    {
        $this->lastPrice = $lastPrice;

        return $this;
    }

    /**
     * Get lastPrice
     *
     * @return string
     */
    public function getLastPrice()
    {
        return $this->lastPrice;
    }

    /**
     * Set lastTrade
     *
     * @param string $lastTrade
     *
     * @return LeasingStockValue
     */
    public function setLastTrade($lastTrade)
    {
        $this->lastTrade = $lastTrade;

        return $this;
    }

    /**
     * Get lastTrade
     *
     * @return string
     */
    public function getLastTrade()
    {
        return $this->lastTrade;
    }

    /**
     * Set changeValue
     *
     * @param string $changeValue
     *
     * @return LeasingStockValue
     */
    public function setChangeValue($changeValue)
    {
        $this->changeValue = $changeValue;

        return $this;
    }

    /**
     * Get changeValue
     *
     * @return string
     */
    public function getChangeValue()
    {
        return $this->changeValue;
    }

    /**
     * Set dividend
     *
     * @param string $dividend
     *
     * @return LeasingStockValue
     */
    public function setDividend($dividend)
    {
        $this->dividend = $dividend;

        return $this;
    }

    /**
     * Get dividend
     *
     * @return string
     */
    public function getDividend()
    {
        return $this->dividend;
    }

    /**
     * Set volume
     *
     * @param string $volume
     *
     * @return LeasingStockValue
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;

        return $this;
    }

    /**
     * Get volume
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * Set yearHigh
     *
     * @param string $yearHigh
     *
     * @return LeasingStockValue
     */
    public function setYearHigh($yearHigh)
    {
        $this->yearHigh = $yearHigh;

        return $this;
    }

    /**
     * Get yearHigh
     *
     * @return string
     */
    public function getYearHigh()
    {
        return $this->yearHigh;
    }

    /**
     * Set yearLow
     *
     * @param string $yearLow
     *
     * @return LeasingStockValue
     */
    public function setYearLow($yearLow)
    {
        $this->yearLow = $yearLow;

        return $this;
    }

    /**
     * Get yearLow
     *
     * @return string
     */
    public function getYearLow()
    {
        return $this->yearLow;
    }

    /**
     * Set dayHigh
     *
     * @param string $dayHigh
     *
     * @return LeasingStockValue
     */
    public function setDayHigh($dayHigh)
    {
        $this->dayHigh = $dayHigh;

        return $this;
    }

    /**
     * Get dayHigh
     *
     * @return string
     */
    public function getDayHigh()
    {
        return $this->dayHigh;
    }

    /**
     * Set DayLow
     *
     * @param string $dayLow
     *
     * @return LeasingStockValue
     */
    public function setDayLow($dayLow)
    {
        $this->dayLow = $dayLow;

        return $this;
    }

    /**
     * Get DayLow
     *
     * @return string
     */
    public function getDayLow()
    {
        return $this->dayLow;
    }

    /**
     * Set OpenPrice
     *
     * @param string $openPrice
     *
     * @return LeasingStockValue
     */
    public function setOpenPrice($openPrice)
    {
        $this->openPrice = $openPrice;

        return $this;
    }

    /**
     * Get OpenPrice
     *
     * @return string
     */
    public function getOpenPrice()
    {
        return $this->openPrice;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     *
     * @return LeasingStockValue
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->created;
    }
    
    /**
     * Update Created Timestamp automatically
     *
     * @ORM\PrePersist
     */
    public function updatedTimestamps()
    {
        if ($this->getCreated() == null) {
            $this->setCreated(new \DateTime('now'));
        }
    }
}
