<?php

namespace CraftKeen\Bundle\TranslationBundle\Model;

use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;

interface LanguageProviderAwareInterface
{
    /**
     * @param LanguageProvider $languageProvider
     */
    public function setLanguageProvider(LanguageProvider $languageProvider);
}
