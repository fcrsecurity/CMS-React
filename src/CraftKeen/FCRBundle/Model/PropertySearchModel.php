<?php

namespace CraftKeen\FCRBundle\Model;

class PropertySearchModel
{
    /**
     * @var int
     */
    protected $isAvailable;

    /**
     * @var string
     */
    protected $category;

    /**
     * @var float
     */
    protected $city;

    /**
     * @var float
     */
    protected $sqftMin;

    /**
     * @var string
     */
    protected $sqftMax;

    /**
     * @var string
     */
    protected $keyword;
    
    public function getIsAvailable()
    {
        return $this->isAvailable;
    }

    public function getCategory()
    {
        return $this->category;
    }

    public function getCity()
    {
        return $this->city;
    }

    public function getSqftMin()
    {
        return $this->sqftMin;
    }

    public function getSqftMax()
    {
        return $this->sqftMax;
    }

    public function getKeyword()
    {
        return $this->keyword;
    }

    public function setIsAvailable($isAvailable)
    {
        $this->isAvailable = $isAvailable;
        return $this;
    }

    public function setCategory($category)
    {
        $this->category = $category;
        return $this;
    }

    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    public function setSqftMin($sqftMin)
    {
        $this->sqftMin = $sqftMin;
        return $this;
    }

    public function setSqftMax($sqftMax)
    {
        $this->sqftMax = $sqftMax;
        return $this;
    }

    public function setKeyword($keyword)
    {
        $this->keyword = $keyword;
        return $this;
    }
}
