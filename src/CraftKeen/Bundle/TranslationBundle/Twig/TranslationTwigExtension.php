<?php

namespace CraftKeen\Bundle\TranslationBundle\Twig;

use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;

class TranslationTwigExtension extends \Twig_Extension
{
    /** @var LanguageProvider */
    protected $languageProvider;

    /**
     * TranslationTwigExtension constructor.
     *
     * @param LanguageProvider $languageProvider
     */
    public function __construct(LanguageProvider $languageProvider)
    {
        $this->languageProvider = $languageProvider;
    }

    /**
     * @return array|\Twig_Function[]
     */
    public function getFunctions()
    {
        return [
            new \Twig_Function('language_current', [$this->languageProvider, 'getCurrentLanguage']),
            new \Twig_Function('available_languages', [$this->languageProvider, 'getLanguages']),
        ];
    }
}
