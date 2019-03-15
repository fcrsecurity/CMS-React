<?php

namespace CraftKeen\FCRBundle\Provider;

use CraftKeen\Bundle\TranslationBundle\Registry\TranslationProviderInterface;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\FCRBundle\Entity\PressRelease;

/**
 * PressRelease Translation Provider
 */
class PressReleaseTranslationProvider implements TranslationProviderInterface
{
    /**
     * {@inheritdoc}
     */
    public function supports($object)
    {
        return $object instanceof PressRelease;
    }

    /**
     * {@inheritdoc}
     */
    public function translate($object, Language $language)
    {
        //TODO: Add logic to find proper translation
        return $object;
    }
}
