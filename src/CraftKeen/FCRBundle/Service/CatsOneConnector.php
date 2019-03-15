<?php

namespace CraftKeen\FCRBundle\Service;

use CraftKeen\FCRBundle\Entity\CareersPosition;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Psr\Log\LoggerInterface;
use CraftKeen\FCRBundle\Entity\CareerPositionSubmission;

/**
 * Class CatsOneConnector
 * @package CraftKeen\FCRBundle\Service
 *
 */
class CatsOneConnector
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
     * @var
     */
    private $apiKey;
    
    /**
     * @var
     */
    private $apiBaseUrl = 'https://api.catsone.com/v3';
    
    /**
     * SoldPropertiesHandler constructor.
     *
     * @param Registry $doctrine
     */
    public function __construct(Registry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
    }
    
    public function getPositions() {
        $uri = '/jobs?per_page=1000';
        
        return $this->sendGetRequest($this->apiBaseUrl.$uri);
    }

    private function sendRequest($url, $extraHeaders = array(), $data = "", $getHeader = false)
    {
        $curl = curl_init();
        $headers = array_merge(
            array(
                'Authorization: Token ' . $this->apiKey,
            ), $extraHeaders
        );
        $params = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HEADER => true,
        );
        if (!empty($data)) {
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt_array($curl, $params);
        curl_setopt($curl, CURLOPT_VERBOSE, true);

        $results = curl_exec($curl);

        if ($getHeader) {
            $header_size = curl_getinfo($curl, CURLINFO_HEADER_SIZE);
            $header = substr($results, 0, $header_size);
            curl_close($curl);
            return $header;
        }

        curl_close($curl);

        return json_decode($results);
    }

    private function sendGetRequest($url, $extraHeaders = array())
    {
        $curl = curl_init();
        $headers = array_merge(
            array(
                'Authorization: Token ' . $this->apiKey,
            ), $extraHeaders
        );
        $params = array(
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_RETURNTRANSFER => true,
            CURLINFO_HEADER_OUT => true,
        );
        curl_setopt_array($curl, $params);
        $results = curl_exec($curl);
        curl_close($curl);
        return json_decode($results);
    }

    /**
     * Get Singular Job Item
     *
     * @param Int $jobID
     * @return JSON
     */
    public function getJob($jobID)
    {
        $url = $this->apiBaseUrl . '/jobs/' . $jobID;
        return $this->sendGetRequest($url, array(), "", true);
    }

    
    /**
     * Submit User Information. Create new Candidate
     * 
     * @param CareerPositionSubmission $careerPositionSubmission
     * @param type $resumePath
     * @return boolean
     */
    public function applyForAJob( CareerPositionSubmission $careerPositionSubmission, $resumePath)
    {
        $candidateId = $this->addNewCandidate($careerPositionSubmission);

        $relation = $this->createPippeline($candidateId, $careerPositionSubmission->getPositionId());

        if (isset($candidateId) && strlen($candidateId) > 0) {
            // Generate URL            
            $url = $this->apiBaseUrl."/candidates/$candidateId/resumes?filename=" . basename($careerPositionSubmission->getResume());
            $respnse = $this->sendRequest($url, array('content-type: application/octet-stream'), file_get_contents($resumePath.DIRECTORY_SEPARATOR.$careerPositionSubmission->getResume()), true);    
            if ( strpos($respnse, "HTTP/1.1 201 Created") ) {
                return true;
            }
        }
        return false;
    }

    public function addNewCandidate(CareerPositionSubmission $careerPositionSubmission)
    {
        // Repare Request URL
        $url = $this->apiBaseUrl . '/candidates?check_duplicate=false';
        // Prepare Candidate information
        $candidate = json_encode(array(
            'first_name' => $careerPositionSubmission->getFirstName(),
            'last_name' =>  $careerPositionSubmission->getLastName(),
            'emails' => array('primary' =>  $careerPositionSubmission->getEmail()),
            'phones' => array('cell' => $careerPositionSubmission->getPhone()),
            'address' => array(
                "street" => $careerPositionSubmission->getAddress(),
                "city" =>  $careerPositionSubmission->getCity(),
                "state" =>  $careerPositionSubmission->getProvince(),
                "postal_code" =>  $careerPositionSubmission->getPostal()
            ),
        ));

        // Add new candidate
        $new = $this->sendRequest($url, array('content-type: application/json'), $candidate, true);
        preg_match_all('/^Location:(.*)$/mi', $new, $matches);
        $candidateId = (int) trim(str_replace($this->apiBaseUrl . "/candidates/", "", $matches[1][0]));

        if (isset($candidateId) && null !== $candidateId) {
            return $candidateId;
        }
        die('Error. Cannot add new Candidate' . __FILE__ . ' line: ' . __LINE__);
    }

    public function createPippeline($candidateId, $jobId, $rating = 0, $statusId = 249)
    {
        $url = $this->apiBaseUrl . '/pipelines';
        $data = json_encode(array(
            'candidate_id' => (int) trim($candidateId),
            'job_id' => (int) trim($jobId),
            'rating' => $rating,
            'status_id' => $statusId,
        ));
        return $this->sendRequest($url, array('content-type: application/json'), $data, true);
    }

    public function secureText($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);
        // transliterate
        $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);
        // trim
        $text = trim($text, '-');
        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);
        // lowercase
        $text = strtolower($text);
        if (empty($text)) {
            return 'n-a';
        }
        return $text;
    }
}
