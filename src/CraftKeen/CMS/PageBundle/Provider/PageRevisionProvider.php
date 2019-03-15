<?php

namespace CraftKeen\CMS\PageBundle\Provider;

use CraftKeen\Bundle\RevisionBundle\Model\RevisionProviderInterface;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class PageRevisionProvider implements RevisionProviderInterface
{
    /** @var ManagerRegistry */
    protected $registry;

    /**
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * @inheritdoc
     */
    public function supports($object)
    {
        return ($object instanceof Page);
    }

    /**
     * @param page $object
     *
     * @return Page
     */
    public function createRevision($object)
    {
        //TODO: Add Logic to create new revision
        return $object;
    }

    /**
     * @param Page $object
     *
     * @return Page
     */
    public function getCurrentVersion($object)
    {
        return $this->getRepository()->findCurrentVersion($object);

    }

    /**
     * @param Page $object
     * @param int $version
     *
     * @return Page|mixed
     */
    public function getVersion($object, $version)
    {
        //TODO: Add logic to retrieve exact revision
        return $object;
    }

    /**
     * @return PageRepository|ObjectRepository
     */
    protected function getRepository() {
        return $this->registry->getRepository(Page::class);
    }

}
