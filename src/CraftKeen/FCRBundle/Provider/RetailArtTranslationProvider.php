<?php

namespace CraftKeen\FCRBundle\Provider;

use Symfony\Bridge\Doctrine\ManagerRegistry;
use CraftKeen\FCRBundle\Entity\RetailArt;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\Bundle\TranslationBundle\Registry\TranslationProviderInterface;

/**
 * Property Translation Provider
 */
class RetailArtTranslationProvider implements TranslationProviderInterface 
{
    /** @var ManagerRegistry */
    protected $registry;
    
    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }
    
    /**
     * {@inheritdoc}
     */
    public function supports($object)
    {
        return $object instanceof RetailArt;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($object, Language $language)
    {        
		if ($object->getLang() == $language) {
            return $object;
        }

        if ($object->getLangParent()) {
            return $this->translate($object->getLangParent(), $language);
        }

        return $this->getRepository()->findTranslation($object, $language) ?: $object;
    }
    
    /**
     * @return PageRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->registry->getRepository(RetailArt::class);
    }
}
