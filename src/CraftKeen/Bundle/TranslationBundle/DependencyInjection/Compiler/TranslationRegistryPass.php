<?php

namespace CraftKeen\Bundle\TranslationBundle\DependencyInjection\Compiler;

use CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler\CompilerPassTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class TranslationRegistryPass implements CompilerPassInterface
{
    use CompilerPassTrait;

    protected $providers = [];

    /**
     * @return string
     */
    public function getRegistryName()
    {
        return 'craft_keen.translation.registry';
    }

    /**
     * @return string
     */
    public function getServiceTag()
    {
        return 'craft_keen.translation.provider';
    }

    /**
     * @return string
     */
    public function getServiceMethod()
    {
        return 'addProvider';
    }
}
