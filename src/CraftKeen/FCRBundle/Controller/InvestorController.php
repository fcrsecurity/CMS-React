<?php

namespace CraftKeen\FCRBundle\Controller;

use CraftKeen\FCRBundle\Entity\HistoricalData;
use CraftKeen\FCRBundle\Entity\HistoricalDataDividend;
use CraftKeen\FCRBundle\Entity\LeasingStockValue;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class InvestorController extends Controller
{
    
    /**
     * Generate Current Stock Value JSON
     * 
     * @Route("/investors/stock-value", name="craftkeen_fcr_investors_stock_values")     
    
     * @return array|Response
     */
    public function stockValuesAction(Request $request) {
        return new JsonResponse ([
                'message' => 'Stock Values Data Fetched',
                'success' => true,
                'data' => $this->getDoctrine()->getRepository(LeasingStockValue::class)->findLatest()
                ]);      
    }
    
	/**
     * Generate Historical Data JSON
     * 
     * @Route("/investors/historical-data/{frequency}", name="craftkeen_fcr_investors_historical_data", requirements={"frequency": ".+"})     
     * 
     * URL Example: ?offset=2&limit=0&filter[dividentsOnly]=true&filter[fromDate]=2002-06-09&filter[toDate]=2003-12-31&filter[timeFrame]=1D
     * @param string $frequency 
     * @return array|Response
     * TODO: only for DAILY frequency, need adjust for Monthly and Weekly
     */
    public function historicalDataAction(Request $request, $frequency) {
		$response = [
			'message' => 'Wrong request',
			'success' => false
		];
		
        if (in_array($frequency, ['daily','weekly','monthly'])) {
            // TODO: Apply Filters
            $filters = [];
            if ($request->query->get('filter')) {
                $filters = $request->query->get('filter');                
            }
            $offset = 0;
            if ($request->query->get('offset')) {
                $offset = (int)$request->query->get('offset');
            }
            $limit = 0;
            if ($request->query->get('limit')) {
                $limit = (int)$request->query->get('limit');
            }
            
            if ( isset($filters['dividentsOnly']) ) {
                $data = $this->getDoctrine()->getRepository(HistoricalDataDividend::class)->findByFrequency($frequency, $filters, $offset, $limit);                
            } else {
                $data = $this->getDoctrine()->getRepository(HistoricalData::class)->findByFrequency($frequency, $filters, $offset, $limit);
            }
            
            $response = [
                'message' => ucwords($frequency).' Historical Data Fetched',
                'success' => true,
                'data' => $data
            ];
        }
		
        return new JsonResponse($response);
    }
	
}
