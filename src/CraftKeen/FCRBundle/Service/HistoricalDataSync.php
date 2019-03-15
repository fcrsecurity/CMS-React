<?php

namespace CraftKeen\FCRBundle\Service;

use Doctrine\ORM\EntityManager;
use CraftKeen\FCRBundle\Entity\HistoricalData;

class HistoricalDataSync
{
    /** @var EntityManager */
    private $em;

    /**
     * HistoricalData constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * Sync with stockWatch.com
     */
    public function  syncWithStockWatch()
    {
        $endDate = date("Y-m-d");
        $url = "http://www.stockwatch.com/webservice/CorporateServices.asmx/CorporateClosePrices?" .
            "Id=firstcapital&Pw=975387&specid=110&Action=quote&StartDate=2002-01-01&EndDate=". $endDate .
            "&Format={date:yyyy-MM-dd},{open},{high},{low},{price:0.0000},{volume}&Options=P";
        $xmlText = simplexml_load_file($url);

        $stringCount = count($xmlText->DataPoints[0]->string);
        for ($i = 0; $i < $stringCount; $i++) {
            $buf = explode(",", $xmlText->DataPoints->string[$i]);
            $historical = $this->em->getRepository(HistoricalData::class)->findOneBy([
                'date' => date_create_from_format('Y-m-d', $buf[0])
            ]);

            if (!$historical){
                $historical = new HistoricalData();
                $historical->setDate( date_create_from_format('Y-m-d', $buf[0]) );
            }

            $historical->setOpen($buf[1]);
            $historical->setHigh($buf[2]);
            $historical->setLow($buf[3]);
            $historical->setClose($buf[4]);
            $historical->setVolume($buf[5]);
            $this->em->persist($historical);
        }
        $this->em->flush();
    }

}
