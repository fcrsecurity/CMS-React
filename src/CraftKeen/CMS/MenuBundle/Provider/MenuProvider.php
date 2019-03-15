<?php

namespace CraftKeen\CMS\MenuBundle\Provider;

use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;
use CraftKeen\CMS\MenuBundle\Entity\Menu;
use CraftKeen\CMS\MenuBundle\Entity\MenuType;
use CraftKeen\CMS\MenuBundle\Exception\MenuTypeNotFoundException;
use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\Common\Cache\CacheProvider;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class MenuProvider
{
    /** @var ManagerRegistry */
    protected $registry;

    /** @var LanguageProvider */
    protected $languageProvider;

    /** @var CacheProvider */
    protected $cache;

    /** @var TokenStorage */
    protected $tokenStorage;

    /** @var bool */
    protected $isCacheEnabled;

    /**
     * @param ManagerRegistry $registry
     * @param LanguageProvider $languageProvider
     * @param TokenStorage $tokenStorage
     * @param CacheProvider $cache
     * @param bool $isCacheEnabled
     */
    public function __construct(
        ManagerRegistry $registry,
        LanguageProvider $languageProvider,
        TokenStorage $tokenStorage,
        CacheProvider $cache,
        $isCacheEnabled = false
    ) {
        $this->registry = $registry;
        $this->languageProvider = $languageProvider;
        $this->cache = $cache;
        $this->tokenStorage = $tokenStorage;
        $this->isCacheEnabled = $isCacheEnabled;
    }

    /**
     * @param string $menuType
     *
     * @return array
     */
    public function getMenuItems($menuType)
    {
        if ($this->isCacheSupported()) {
            $cacheKey = $this->getCacheKey($menuType);
            if (!$this->cache->contains($cacheKey)) {
                $data = $this->findMenuItems($menuType);
                $this->cache->save($cacheKey, serialize($data));
                return $data;
            }
            return unserialize($this->cache->fetch($cacheKey));
        } else {
            return $this->findMenuItems($menuType);
        }
    }

    /**
     * @param string $menuType
     *
     * @return string
     */
    protected function getCacheKey($menuType)
    {
        $lang = $this->languageProvider->getCurrentLanguage()->getCode();

        $user = 'anonymous';

        if ($this->getUser()) {
            $user = 'authenticated';
        }

        return sprintf('menu-%s-%s-%s', strtolower($menuType), strtolower($lang), $user);
    }

    /**
     * @return bool
     */
    protected function isCacheSupported()
    {
        return $this->isCacheEnabled;
    }

    /**
     * @return User|null
     */
    protected function getUser()
    {
        $user = $this->tokenStorage->getToken()->getUser();
        if ($user instanceof User) {
            return $user;
        }

        return null;
    }

    /**
     * @param string $menuType
     * @return array
     * @throws MenuTypeNotFoundException
     */
    protected function findMenuItems($menuType)
    {
        $menuTree = [];
        $dataSet = [];

        $type = $this->getRepository(MenuType::class)->findOneBy(['name' => $menuType,]);
        if (!$type) {
            throw new MenuTypeNotFoundException($type);
        }

        $menuItems = $this->getRepository(Menu::class)->findBy(
            ['type' => $type, 'lang' => $this->languageProvider->getCurrentLanguage(), 'status' => 'live'],
            ['sortOrder' => 'ASC']
        );

        /** @var Menu $menuItem */
        foreach ($menuItems as $menuItem) {
            $link = $menuItem->getMenuLink();
            if (null === $link) {
                continue;
            }

            if (strlen($menuItem->getName()) > 0) {
                $dataSet[$menuItem->getId()] = [
                    'name' => $menuItem->getName(),
                    'parent' => $menuItem->getParent(),
                    'linkType' => $menuItem->getItemType(),
                    'link' => $link,
                    'targetBlank' => $menuItem->getTargetBlank(),
                ];
            }
        }
        foreach ($dataSet as $id => &$node) {
            if (empty($node['parent'])) {
                $menuTree[$id] = &$node;
            } else {
                /** @var Menu $parent */
                $parent = $node['parent'];
                $dataSet[$parent->getId()]['children'][$id] = &$node;
            }
        }

        $menuTree = array_filter($menuTree, function ($item) use ($menuTree) {
            if (!isset($item['link'])) {
                return false;
            }
            return true;
        });

        return $menuTree;
    }

    /**
     * @param $class
     *
     * @return ObjectRepository
     */
    protected function getRepository($class)
    {
        return $this->registry->getManagerForClass($class)->getRepository($class);
    }
}
