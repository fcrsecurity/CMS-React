<?php

namespace CraftKeen\Bundle\TranslationBundle\Traits;

use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;
use CraftKeen\CMS\AdminBundle\Entity\Language;

trait LanguageProviderAwareTrait
{
    /** @var LanguageProvider */
    protected $languageProvider;

    /**
     * @param LanguageProvider $languageProvider
     */
    public function setLanguageProvider(LanguageProvider $languageProvider)
    {
        $this->languageProvider = $languageProvider;
    }

    /**
     * @return Language|null
     */
    protected function getCurrentLanguage()
    {
        return $this->languageProvider->getCurrentLanguage();
    }
}
