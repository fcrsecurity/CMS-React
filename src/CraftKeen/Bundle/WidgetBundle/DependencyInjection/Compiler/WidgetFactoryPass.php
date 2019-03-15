<?php

namespace CraftKeen\Bundle\WidgetBundle\DependencyInjection\Compiler;

use CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler\CompilerPassTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class WidgetFactoryPass implements CompilerPassInterface
{
    use CompilerPassTrait;

    protected $providers = [];

    /**
     * @return string
     */
    public function getRegistryName()
    {
        return 'craft_keen_widget.factory';
    }

    /**
     * @return string
     */
    public function getServiceTag()
    {
        return 'craft_keen_widget.widget';
    }

    /**
     * @return string
     */
    public function getServiceMethod()
    {
        return 'addWidget';
    }
}
