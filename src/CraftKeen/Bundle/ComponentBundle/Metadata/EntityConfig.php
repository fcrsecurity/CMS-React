<?php

namespace CraftKeen\Bundle\ComponentBundle\Metadata;

use Doctrine\Common\Annotations\Annotation;

/**
 * @Annotation
 * @Target("CLASS")
 */
class EntityConfig
{
    const NAME = 'EntityConfig';
    const ROUTE_SLUG = 'route_slug';
    const ROUTE_VIEW = 'route_view';

    protected $routes;

    /**
     * @param array $data
     */
    public function __construct(array $data)
    {
        if (isset($data['routes'])) {
            $this->routes = $data['routes'];
        }
    }

    /**
     * @param string $name
     *
     * @return string|null
     */
    public function getRoute($name)
    {
        return isset($this->routes[$name]) ? $this->routes[$name] : null;
    }

    /**
     * @return null|string
     */
    public function getSlugRoute()
    {
        return $this->getRoute(self::ROUTE_SLUG);
    }

    /**
     * @return null|string
     */
    public function getViewRoute()
    {
        return $this->getRoute(self::ROUTE_SLUG);
    }
}
