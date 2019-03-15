<?php

namespace CraftKeen\FCRBundle\Provider;

use Doctrine\ORM\EntityManager;
use CraftKeen\FCRBundle\Entity\Property;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\Bundle\TranslationBundle\Registry\TranslationProviderInterface;

/**
 * Property Translation Provider
 */
class PropertyTranslationProvider implements TranslationProviderInterface 
{
    /** @var EntityManager */
    private $em;
    
    /**
     * PropertyTranslationProvider constructor.
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supports($object)
    {
        return $object instanceof Property;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($object, Language $language)
    {
		// Check for Existing translation
        if ( $language !== $object->getLang() ) {
            $objectTranslation = $this->em->getRepository(Property::class)->findOneBy(['lang' => $language, 'langParent' => $object]); 
			
			if (!$objectTranslation) {
				return $object;
			}
			// Copy Relations from Parent
			$objectTranslation->setFilters($object->getFilters());
			$objectTranslation->setDemographic($object->getDemographic());
			
			foreach ($object->getVacancyList() as $i) {
				$objectTranslation->addVacancyList($i);
			}
			foreach ($object->getGallery() as $i) {
				$objectTranslation->addGallery($i);
			}
			
            return $objectTranslation;
        }         
        return $object;
    }
}
