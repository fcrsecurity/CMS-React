<?php

namespace CraftKeen\Bundle\TranslationBundle\Registry;

use CraftKeen\CMS\AdminBundle\Entity\Language;

class TranslationRegistry
{
    /** @var array|TranslationProviderInterface[] */
    protected $providers = [];

    /**
     * @param TranslationProviderInterface $provider
     * @param $alias
     *
     * @return $this
     */
    public function addProvider(TranslationProviderInterface $provider, $alias)
    {
        $this->providers[$alias] = $provider;

        return $this;
    }

    /**
     * @param mixed $object
     * @param mixed Language $language
     *
     * @return mixed|null
     */
    public function translate($object, Language $language)
    {
        /** @var TranslationProviderInterface $provider */
        foreach ($this->providers as $provider) {
            if ($provider->supports($object)) {
                return $provider->translate($object, $language);
            }
        }

        return null;
    }

    /**
     * @return array|TranslationProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }
}
