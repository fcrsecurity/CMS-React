<?php

namespace CraftKeen\Bundle\SearchBundle\Provider;

use CraftKeen\Bundle\SearchBundle\Entity\SearchIndexItem;
use CraftKeen\Bundle\SearchBundle\Model\SearchableInterface;
use CraftKeen\Bundle\SearchBundle\Model\SearchResult;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class DatabaseSearchProvider implements SearchProviderInterface
{
    /** @var ManagerRegistry */
    protected $registry;

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @param ManagerRegistry $registry
     * @param ContainerInterface $container
     * @param RequestStack $requestStack
     */
    public function __construct(ManagerRegistry $registry, ContainerInterface $container, RequestStack $requestStack)
    {
        $this->registry = $registry;
        $this->container = $container;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function search($query, $offset = 0, $limit = null)
    {
        $language = $this->container->get('craft_keen.translation.provider.language')->getCurrentLanguage();

        $qb = $this->getRepository()->createQueryBuilder('s');
        $qb->distinct(true);
        $qb->where($qb->expr()->like("s.title", "'%" . $query . "%'"));
        $qb->orWhere($qb->expr()->like("s.shortBody", "'%" . $query . "%'"));
        $qb->orWhere($qb->expr()->like("s.hiddenMeta", "'%" . $query . "%'"));
        $qb->andWhere('s.language = :language');
        $qb->setParameter(':language', $language);
        $qb->addOrderBy('s.weight', 'ASC');

        //TODO: Add Pagination Support
        $items = $qb->getQuery()->getResult();

        /** @var SearchableInterface $item */
        foreach ($items as $k => $item) {
            $object = $this->registry->getRepository($item->getObjectClass())->find($item->getObjectId());
            if (!$object) {
                $this->getManager()->remove($item);
                unset($items[$k]);
                continue;
            }
            $item->setObject($object);
        }

        $result = new SearchResult();

        $this->getManager()->flush();

        return $result->setMatches($items)->setMatchesFound(count($items));
    }

    /**
     * @param $class
     *
     * @return ObjectRepository|EntityRepository
     */
    protected function getRepository($class = SearchIndexItem::class)
    {
        return $this->registry->getRepository($class);
    }

    /**
     * @param $class
     *
     * @return EntityManager|ObjectManager
     */
    protected function getManager($class = SearchIndexItem::class)
    {
        return $this->registry->getManagerForClass($class);
    }
}
