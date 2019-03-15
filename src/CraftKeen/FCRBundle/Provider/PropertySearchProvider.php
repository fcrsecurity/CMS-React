<?php

namespace CraftKeen\FCRBundle\Provider;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Persistence\ObjectManager;
use CraftKeen\FCRBundle\Entity\Property;

/**
 * Class PropertySearchProvider
 *
 */
class PropertySearchProvider
{
    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @param Registry $registry
     */
    public function __construct(Registry $registry)
    {
        $this->doctrine = $registry;
    }    

    /**
     * @return array
     */
    public function getCategoryTypeChoices()
    {
		return [
            'All Properties' => 'all_default',
            'Grocery Anchored' => 'grocery_anchored',
            'Office Space' => 'office_space',
			'Retail' => 'urban_retail',
			'Under Development' => 'under_development',
        ];
    }
    
    /**
     * Get Sorter List of All Cities.
     * 
     * @return array
     */
    public function getCityTypeChoices()
    {
        $repository = $this->doctrine->getRepository(Property::class);                     
        $properties = $repository->findBy(['isHidden' => 0]);            
        $cities = $repository->getCityListFromProperties($properties, true);
        return $cities;        
    }
}
