<?php

namespace CraftKeen\CMS\AdminBundle\Twig;

/**
 * Class PageExtension
 * @package CraftKeen\CMS\AdminBundle\Twig
 */
class PageExtension extends \Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('findFilterName', [$this, 'findFilterNameFilter']),
        ];
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return [
            'class' => new \Twig_SimpleFunction('class', [$this, 'getClass']),
        ];
    }

    /**
     * Checks if route exists
     *
     * @param $value
     * @param string $filterName
     *
     * @return mixed|string
     */
    public function findFilterNameFilter($value, $filterName = '')
    {
        if (is_array($value)) {
            if (isset($value[$filterName])) {
                return $value[$filterName];
            }
            return 'N\A';
        }
        return $value;
    }

    /**
     * @param $object
     *
     * @return string
     */
    public function getClass($object)
    {
        return (new \ReflectionClass($object))->getShortName();
    }
}
