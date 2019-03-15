<?php

namespace CraftKeen\Bundle\RevisionBundle\Registry;

use CraftKeen\Bundle\RevisionBundle\Exception\RevisionProviderNotFoundException;
use CraftKeen\Bundle\RevisionBundle\Model\RevisionProviderInterface;

class RevisionRegistry implements RevisionProviderInterface
{
    /** @var array|RevisionProviderInterface[] */
    protected $providers = [];

    /**
     * @param RevisionProviderInterface $provider
     * @param $alias
     */
    public function addProvider(RevisionProviderInterface $provider, $alias)
    {
        $this->providers[$alias] = $provider;
    }

    /**
     * @return array|RevisionProviderInterface[]
     */
    public function getProviders()
    {
        return $this->providers;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($object)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($object)) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function createRevision($object)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($object)) {
                return $provider->createRevision($object);
            }
        }

        throw new RevisionProviderNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentVersion($object)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($object)) {
                return $provider->getCurrentVersion($object);
            }
        }

        throw new RevisionProviderNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function getVersion($object, $version)
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($object)) {
                return $provider->getVersion($object, $version);
            }
        }

        throw new RevisionProviderNotFoundException();
    }
}
