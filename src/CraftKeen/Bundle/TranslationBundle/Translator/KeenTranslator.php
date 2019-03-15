<?php

namespace CraftKeen\Bundle\TranslationBundle\Translator;

use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;
use Symfony\Component\Translation\TranslatorInterface;

class KeenTranslator implements TranslatorInterface
{
    /** @var TranslatorInterface */
    protected $translator;

    /** @var LanguageProvider */
    protected $languageProvider;

    /**
     * @inheritDoc
     */
    public function __construct(TranslatorInterface $translator, LanguageProvider $languageProvider)
    {
        $this->translator = $translator;
        $this->languageProvider = $languageProvider;
    }

    /**
     * @inheritDoc
     */
    public function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        $result = $this->translator->trans($id, $parameters, $domain, $this->resolveLocale($locale));

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $domain, $this->resolveLocale($locale));
    }

    /**
     * @inheritDoc
     */
    public function setLocale($locale)
    {
        $this->translator->setLocale($locale);
    }

    /**
     * @inheritDoc
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     * @param null $locale
     */
    protected function resolveLocale($locale = null)
    {
        if (null === $locale) {
            return $this->languageProvider->getCurrentLanguage()->getLocale();
        }
        $language = $this->languageProvider->getLanguage($locale);
        if (!$language) {
            return $this->languageProvider->getCurrentLanguage()->getLocale();
        }

        return $locale;
    }
}
