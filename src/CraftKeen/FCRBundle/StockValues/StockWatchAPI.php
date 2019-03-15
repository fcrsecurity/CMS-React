<?php

namespace CraftKeen\FCRBundle\StockValues;

use Psr\Log\LoggerInterface;

/**
 * Class StockWatchAPI
 * @package CraftKeen\FCRBundle\StockValues
 */
class StockWatchAPI implements StockValuesAPIInterface
{
    private $results;
    
    /**
     * Connect to the Feed and Convert Response to Array
     * @return void
     */
    public function fetch(LoggerInterface $logger)
    {
        $url = 'http://www.stockwatch.com/webservice/CorporateServices.asmx/CorporateSnapshot?Id=firstcapital&Pw=975387&specid=110&Action=quote';
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FAILONERROR => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_TIMEOUT => 15,
        ));
        $results = curl_exec($curl);
        curl_close($curl);

        if (null !== $results) {
            $stockData = new \SimpleXMLElement($results);
            $this->results = (array)$stockData->Quotes->Quote;
            $logger->info('Values retrieved.');
        } else {
            $logger->critical('Cannot Retrieve data! Feed is not working.');
        }
    }

    /**
     * Get Year Price Value
     *
     * @return string
     */
    public function getLastPrice()
    {
        return $this->preformatNumber($this->results['LastPrice']);
    }
    
    /**
     * Get Last Trade Value
     *
     * @return string
     */
    public function getLastTrade()
    {
        return new \DateTime($this->results['LastTrade']);
    }
    
    /**
     * Get Change Value
     *
     * @return string
     */
    public function getChangeValue()
    {
        return round($this->results['Change'], 2);
    }
    
    /**
     * Get Dividend Value
     *
     * @return string
     */
    public function getDividend()
    {
        $diff = ($this->getChangeValue() * 100) / ($this->getLastPrice() - $this->getChangeValue());
        return round($diff, 2) . '%';
    }
    
    /**
     * Get Volume Value
     *
     * @return string
     */
    public function getVolume()
    {
        return $this->results['Volume'];
    }
    
    /**
     * Get Year High Value
     *
     * @return string
     */
    public function getYearHigh()
    {
        return $this->preformatNumber($this->results['YearHigh']);
    }
    
    /**
     * Get Year Low Value
     *
     * @return string
     */
    public function getYearLow()
    {
        return $this->preformatNumber($this->results['YearLow']);
    }

    /**
     * Get Day High Value
     *
     * @return string
     */
    public function getDayHigh()
    {
        return $this->preformatNumber($this->results['DayHigh']);
    }

    /**
     * Get Day Low Value
     *
     * @return string
     */
    public function getDayLow()
    {
        return $this->preformatNumber($this->results['DayLow']);
    }

    /**
     * Get Day Open Price
     *
     * @return string
     */
    public function getOpenPrice()
    {
        return $this->preformatNumber($this->results['OpenPrice']);
    }
    
    /**
     * Apply Custom Filters for number
     *
     * @param string $number
     * @return string
     */
    private function preformatNumber($number)
    {
        return number_format(round($number, 2), 2, '.', '');
    }
}
