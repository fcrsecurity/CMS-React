<?php

namespace CraftKeen\FCRBundle\StockValues;

use Psr\Log\LoggerInterface;

/**
 * Interface StockValuesAPIInterface
 * @package CraftKeen\FCRBundle\StockValues
 */
interface StockValuesAPIInterface
{
    
    /**
     * @return array
     */
    public function fetch(LoggerInterface $logger);
    
    /**
     * @return string
     */
    public function getLastPrice();
    
    /**
     * @return string
     */
    public function getLastTrade();
    
    /**
     * @return string
     */
    public function getChangeValue();
    
    /**
     * @return string
     */
    public function getDividend();
    
    /**
     * @return string
     */
    public function getVolume();
    
    /**
     * @return string
     */
    public function getYearHigh();
    
    /**
     * @return string
     */
    public function getYearLow();

    /**
     * @return string
     */
    public function getDayHigh();

    /**
     * @return string
     */
    public function getDayLow();
}
