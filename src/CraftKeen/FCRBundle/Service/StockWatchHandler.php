<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\LeasingStockValue;
use CraftKeen\FCRBundle\StockValues\StockWatchAPI;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;

/**
 * Class StockValuesHandlerInterface
 * @package CraftKeen\FCRBundle\Service
 *
 * Updated Values in DB and Remove old
 */
class StockWatchHandler
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var
     */
    protected $logger;

    /**
     * SoldPropertiesHandler constructor.
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    /**
     * Handle StockWatch API connection and updating Stock Values Table
     *
     * @param LoggerInterface $logger
     */
    public function handle(LoggerInterface $logger)
    {
        $this->removeOldValues();
        
        $feed = new StockWatchAPI();
        $feed->fetch($logger);
        $em = $this->doctrine->getManagerForClass(LeasingStockValue::class);
        $stockValue = new LeasingStockValue();
        $stockValue->setChangeValue($feed->getChangeValue());
        $stockValue->setDividend($feed->getDividend());
        $stockValue->setLastPrice($feed->getLastPrice());
        $stockValue->setLastTrade($feed->getLastTrade());
        $stockValue->setVolume($feed->getVolume());
        $stockValue->setDayHigh($feed->getDayHigh());
        $stockValue->setDayLow($feed->getDayLow());
        $stockValue->setYearHigh($feed->getYearHigh());
        $stockValue->setYearLow($feed->getYearLow());
        $stockValue->setOpenPrice($feed->getOpenPrice());
        $em->persist($stockValue);
        $em->flush();
    }

    /**
     * Returns the latest values from StockValues table
     *
     * @return mixed
     */
    public function retrieveLatestValues()
    {
        return $this->doctrine
                ->getRepository(LeasingStockValue::class)
                ->findLatest();
    }

    /**
     * Removes old values from database
     *
     * @param string $period
     * @return mixed
     */
    private function removeOldValues($period= '-2days')
    {
        $recentDate = date('Y-m-d H:i:s', strtotime($period));
        /** @var EntityManager $em */
        $em = $this->doctrine->getManagerForClass(LeasingStockValue::class);
        /** @var EntityRepository $repo */
        $repo = $em->getRepository(LeasingStockValue::class);
        $qb = $repo->createQueryBuilder('sv');
        $query = $qb->delete()
            ->andWhere('sv.created <= :recentDate')
            ->setParameter('recentDate', $recentDate)->getQuery();
                
        $removedPropertiesCount = $query->execute();

        return $removedPropertiesCount;
    }
}
