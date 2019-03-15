<?php

namespace CraftKeen\Bundle\TranslationBundle\Registry;

use CraftKeen\CMS\AdminBundle\Entity\Language;

interface TranslationProviderInterface
{
    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function supports($object);

    /**
     * @param mixed $object
     * @param Language $language
     *
     * @return mixed
     */
    public function translate($object, Language $language);
}
