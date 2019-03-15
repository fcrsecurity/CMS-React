<?php

namespace CraftKeen\FCRBundle\Import;

use CraftKeen\FCRBundle\Entity\HistoricalData;
use CraftKeen\FCRBundle\Entity\HistoricalDataDividend;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class HistoricalDataImport extends AbstractImport
{
    /**
     * PressReleaseImport constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * Clean up tables
     *
     * @return void
     */
    public function eraseTables()
    {
        $this->em->getRepository(HistoricalData::class)->truncate();
    }

    /**
     * Load Dependent Tables
     *
     * @return void
     */
    public function loadDependencies()
    {
        if (!isset($this->records['historical_data']) && !isset($this->records['historical_data_dividends'])) {
            throw new Exception('Dependencies was not loaded properly');
        }

        $response['historical_data_dividends'] = $this->loadHistoricalDataDividends($this->records['historical_data_dividends']);
        $response['historical_data'] = $this->loadHistoricalData($this->records['historical_data']);

        return $response;
    }


    /**
     * Load Historical Dividends
     *
     * @param array $items
     * @return void
     */
    private function loadHistoricalDataDividends($items)
    {
        $count = 0;
        foreach ($items as $key => $item) {

            date_default_timezone_set('America/Toronto');
            $date = \DateTime::createFromFormat('Y-m-d', $item['Date']);

            $historicalDataD = new HistoricalDataDividend();
            $historicalDataD->setDate($date);
            $historicalDataD->setDividend($item['Dividends']);
            $this->em->persist($historicalDataD);
            $count++;
        }
        $this->em->flush();
		return $count;
    }

    /**
     * Load Historical Data
     *
     * @param array $items
     * @return void
     */
    private function loadHistoricalData($items)
    {
        $count = 0;

        foreach ($items as $key => $item) {
            date_default_timezone_set('America/Toronto');
            $date = \DateTime::createFromFormat('Y-m-d', $item['Date']);

            $historicalData = new HistoricalData();

            $historicalData->setDate($date);
            $historicalData->setOpen($this->filterValue($item['Open']));
            $historicalData->setHigh($this->filterValue($item['High']));
            $historicalData->setLow($this->filterValue($item['Low']));
            $historicalData->setClose($this->filterValue($item['Close']));
            $historicalData->setAdjClose($this->filterValue($item['Adj Close']));
            $historicalData->setVolume($this->filterValue($item['Volume']));

            $this->em->persist($historicalData);
            $count++;
        }
        $this->em->flush();
		return $count;
    }

    private function filterValue($value)
    {
        $volume = null;
        if ("null" !== $value) {
            $volume = floatval($value);
        }

        return $volume;
    }
}
