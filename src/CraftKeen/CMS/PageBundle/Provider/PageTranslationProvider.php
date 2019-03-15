<?php

namespace CraftKeen\CMS\PageBundle\Provider;

use CraftKeen\Bundle\TranslationBundle\Registry\TranslationProviderInterface;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Repository\PageRepository;
use Doctrine\Common\Persistence\ObjectRepository;
use Symfony\Bridge\Doctrine\ManagerRegistry;

class PageTranslationProvider implements TranslationProviderInterface
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
     * {@inheritdoc}
     */
    public function supports($object)
    {
        return $object instanceof Page;
    }

    /**
     * @param Page $object
     * @param Language $language
     *
     * @return Page
     */
    public function translate($object, Language $language)
    {
        if ($object->getLang() == $language) {
            return $object;
        }

        if ($object->getLangParent()) {
            return $this->translate($object->getLangParent(), $language);
        }
        
        return $this->getRepository()->findTranslation($object, $language) ?: $object;
    }

    /**
     * @return PageRepository|ObjectRepository
     */
    protected function getRepository()
    {
        return $this->registry->getRepository(Page::class);
    }
}
