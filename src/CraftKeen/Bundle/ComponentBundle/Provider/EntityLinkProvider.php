<?php

namespace CraftKeen\Bundle\ComponentBundle\Provider;

use CraftKeen\Bundle\ComponentBundle\Metadata\EntityConfig;
use CraftKeen\Bundle\TranslationBundle\Provider\LanguageProvider;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Routing\Router;

class EntityLinkProvider
{
    /** @var Reader */
    protected $annotationReader;

    /** @var Router */
    protected $router;

    /** @var LanguageProvider */
    protected $languageProvider;

    /**
     * @param Reader $annotationReader
     * @param Router $router
     * @param LanguageProvider $languageProvider
     */
    public function __construct(Reader $annotationReader, Router $router, LanguageProvider $languageProvider)
    {
        $this->annotationReader = $annotationReader;
        $this->router = $router;
        $this->languageProvider = $languageProvider;
    }

    /**
     * @param mixed $entity
     *
     * @return string
     */
    public function getSlugLink($entity)
    {
        $data = $this->getMetaData($entity);

        if (!$data || !$data->getSlugRoute() || !$entity->getSlug()) {
            return '#';
        }

        $slug = $this->router->generate(
            $data->getSlugRoute(),
            ['slug' => $entity->getSlug()]
        );

        if ('//' == $slug) {
            $slug = '/';
        }

        return $slug;
    }

    /**
     * @param mixed $entity
     *
     * @return string
     */
    public function getViewLink($entity)
    {
        $data = $this->getMetaData($entity);

        if (!$data || !$data->getViewRoute()) {
            return '#';
        }

        return $this->router->generate(
            $data->getViewRoute(),
            ['id' => $entity->getId()]
        );
    }

    /**
     * @param object $entity
     *
     * @return null|object|EntityConfig
     */
    protected function getMetaData($entity)
    {
        if (!is_object($entity)) {
            return null;
        }

        $annotations = $this->annotationReader->getClassAnnotations(new \ReflectionClass($entity));
        foreach ($annotations as $annotation) {
            if ($annotation instanceof EntityConfig) {
                return $annotation;
            }
        }

        return null;
    }
}
