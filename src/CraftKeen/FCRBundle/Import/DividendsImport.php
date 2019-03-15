<?php

namespace CraftKeen\FCRBundle\Import;

use CraftKeen\FCRBundle\Entity\Dividend;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class DividendsImport extends AbstractImport
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
        $this->em->getRepository(Dividend::class)->truncate();
    }
    
    /**
     * Load Dependent Tables
     * 
     * @return void
     */
    public function loadDependencies()
    {
        if (!isset($this->records['dividends'])) {
            throw new Exception('Dependencies was not loaded properly');
        }

        $response['dividends'] = $this->loadDividends($this->records['dividends']);
       
        return $response;
    }
       

    /**
     * Load Historical Dividends
     *
     * @param array $items
     * @return void
     */
    private function loadDividends($items)
    {
        date_default_timezone_set('America/Toronto'); 
        $count = 0;

        foreach ($items as $key => $item) { 
            $dividend = new Dividend(); 
            $dividend->setDeclaredDate($this->formatDataValue($item['declared_date']));
            $dividend->setExDividendDate($this->formatDataValue($item['ex_dividend_date']));
            $dividend->setRecordDate($this->formatDataValue( $item['record_date']));
            $dividend->setPayableDate($this->formatDataValue($item['payable_date']));
            $dividend->setSpecialDividendInKind($this->formatDataValue( $item['special_dividend_in_kind']));
            $dividend->setDividendAmount( (float)$item['dividend_amount'] );            
            $dividend->setStatus('live');
            $dividend->setLang($this->lang['en']);
            $dividend->setCreatedBy($this->user);

            $this->em->persist($dividend);
            $this->em->flush();
            $count++;
        }
        
		return $count;
    }
    
    function formatDataValue($value) {
        if ( "null" != $value ) {
            $date = \DateTime::createFromFormat('Y-m-d', $value);
            if ( $date ) {
               return $date;
            }
        }
        return null;
    }
}
