<?php

namespace CraftKeen\Bundle\SearchBundle\Indexer;

use CraftKeen\Bundle\SearchBundle\Entity\Repository\SearchIndexItemRepository;
use CraftKeen\Bundle\SearchBundle\Entity\SearchIndexItem;
use CraftKeen\Bundle\SearchBundle\Model\SearchableInterface;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;

class DatabaseSearchIndexer implements SearchIndexerInterface
{
    /** @var ManagerRegistry */
    protected $registry;

    /**
     * DatabaseSearchIndexer constructor.
     *
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function add(SearchableInterface $item)
    {
        $em = $this->getManager();

        $search = $this->find($item->getObjectClass(), $item->getObjectId());
        if (!$search) {
            $search = new SearchIndexItem();
            $em->persist($search);
        }

        $search->setTitle($item->getTitle())
            ->setShortBody($item->getShortBody())
            ->setHiddenMeta($item->getHiddenMeta())
            ->setObjectId($item->getObjectId())
            ->setObjectClass($item->getObjectClass())
            ->setSite($item->getSite() ?: $this->getDefaultSite())
            ->setLanguage($item->getLanguage() ?: $this->getDefaultLanguage())
            ->setWeight($item->getWeight());

        $em->flush($search);
    }

    /**
     * {@inheritdoc}
     */
    public function remove(SearchableInterface $item)
    {
        $em = $this->getManager();
        $search = $this->find($item->getObjectClass(), $item->getObjectId());
        if ($search) {
            $em->remove($search);
        }

        $em->flush($search);
    }

    /**
     * @param string $objectClass
     * @param int $objectId
     *
     * @return SearchIndexItem|null|object
     */
    protected function find($objectClass, $objectId)
    {
        return $this->getRepository()->findOneBy(['objectClass' => $objectClass, 'objectId' => $objectId]);
    }

    /**
     * @return SearchIndexItemRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->registry->getRepository(SearchIndexItem::class);
    }

    /**
     * @return ObjectManager|null|EntityManager
     */
    protected function getManager()
    {
        return $this->registry->getManagerForClass(SearchIndexItem::class);
    }

    /**
     * @return Site
     */
    protected function getDefaultSite()
    {
        return new Site();
    }

    /**
     * @return Language
     */
    protected function getDefaultLanguage()
    {
        return new Language();
    }
}
