<?php

namespace CraftKeen\Bundle\TranslationBundle\Provider;

use CraftKeen\Bundle\TranslationBundle\Exception\LanguageNotFoundException;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Repository\LanguageRepository;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class LanguageProvider
{
    const CACHE_TTL = 0;

    /** @var RequestStack */
    protected $requestStack;

    /** @var Registry */
    protected $doctrine;

    /** @var CacheProvider */
    protected $cacheProvider;

    /**
     * LanguageProvider constructor.
     *
     * @param RequestStack $requestStack
     * @param Registry $doctrine
     * @param CacheProvider $cacheProvider
     * @param string $defaultLocale
     */
    public function __construct(
        RequestStack $requestStack,
        Registry $doctrine,
        CacheProvider $cacheProvider,
        $defaultLocale
    ) {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
        $this->cacheProvider = $cacheProvider;
        $this->defaultLocale = $defaultLocale;
    }

    /**
     * @return Language|null
     */
    public function getCurrentLanguage()
    {
        $request = $this->requestStack->getMasterRequest();

        return $this->getLanguage($request->getLocale());
    }

    /**
     * @param string $locale
     *
     * @return Language|null
     */
    public function getLanguage($locale)
    {
        $language = $this->fetchCache($locale);
        if (!$language) {
            $language = $this->findLanguage($locale);
            $this->cacheProvider->save($locale, $language);
        }

        if (!$language) {
            throw new LanguageNotFoundException($locale);
        }

        return $language;
    }

    /**
     * @return Language[]|array
     */
    public function getLanguages()
    {
        $languages = $this->fetchCache('languages');
        if (!$languages) {
            $languages = $this->getRepository()->findAll();
            $this->cacheProvider->save('languages', $languages);
        }

        return $languages;
    }

    /**
     * @param $locale
     *
     * @return null|Language|object
     */
    protected function findLanguage($locale)
    {
        if (!$locale) {
            return null;
        }

        $language = $this->getRepository()->findOneBy(['locale' => $locale]);

        if (!$language) {
            $language = $this->getRepository()->findOneBy(['code' => $locale]);
        }

        return $language;
    }

    /**
     * @return Language|null
     */
    public function getDefaultLanguage()
    {
        return $this->getLanguage($this->defaultLocale);
    }

    /**
     * @param $locale
     *
     * @return null|Language
     */
    protected function fetchCache($locale)
    {
        return $this->cacheProvider->fetch($locale) ?: null;
    }

    /**
     * @return LanguageRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->doctrine->getRepository(Language::class);
    }
}
