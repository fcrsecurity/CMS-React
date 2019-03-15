<?php

namespace CraftKeen\Bundle\RevisionBundle\Model;

interface RevisionProviderInterface
{
    /**
     * @param mixed $object
     *
     * @return bool
     */
    public function supports($object);

    /**
     * @param mixed $object
     *
     * @return mixed
     */
    public function createRevision($object);

    /**
     * @param $object
     *
     * @return mixed
     */
    public function getCurrentVersion($object);

    /**
     * @param mixed $object
     * @param int $version
     *
     * @return mixed
     */
    public function getVersion($object, $version);
}
