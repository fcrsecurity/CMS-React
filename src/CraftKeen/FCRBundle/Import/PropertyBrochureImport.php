<?php

namespace CraftKeen\FCRBundle\Import;

use CraftKeen\FCRBundle\Entity\Property;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;

class PropertyBrochureImport extends AbstractImport
{
    /**
     * PropertyBrochureImport constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * Erases Tables Before Import
     *
     * @return mixed
     */
    public function eraseTables()
    {
        // No need to erase tables for this import.
      return true;
    }

    public function loadDependencies()
    {
        if (!isset($this->records['property_brochures'])) {
            throw new Exception('Cannot find Property Brochures');
        }
        $brochureURL = 'https://fcr.ca/uploads/properties/marketing-pdfs/';
        $message['added'] = [];
        $message['updated'] = [];
        $message['wrong_property_code'] = [];
        if ( count($this->records['property_brochures']) > 0 ) {
            foreach ($this->records['property_brochures'] as $brochure) {
                $properties = $this->em->getRepository(Property::class)->findByCode($brochure['code']);

                if (null == $properties) {
                    $message['wrong_property_code'][] = $brochure['code'];
                }
                /** @var Property $property */
                foreach ($properties as $property) {
                    $existingPdf = $property->getDetails()->getMarketingPdf();
                    $property->getDetails()->setMarketingPdf($brochureURL.$brochure['marketing_pdf']);
                    // Make sure there is value.
                    // TODO: All Pdf File check.
                    if (null == $brochure['marketing_pdf'] || 'null' == strtolower($brochure['marketing_pdf']) || strlen($brochure['marketing_pdf']) == 0) {
                        $property->getDetails()->setMarketingPdf(null);
                    }
                    if (null == $existingPdf) {
                        $message['added'][] = $brochure['code'];
                    } else {
                        $message['updated'][] = $brochure['code'];
                    }
                }
            }

            $this->em->flush();
        }

        return $message;
    }
}
