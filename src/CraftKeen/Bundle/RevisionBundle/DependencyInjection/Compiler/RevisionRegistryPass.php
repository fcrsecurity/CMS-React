<?php

namespace CraftKeen\Bundle\RevisionBundle\DependencyInjection\Compiler;

use CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler\CompilerPassTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class RevisionRegistryPass implements CompilerPassInterface
{
    use CompilerPassTrait;

    protected $providers = [];

    /**
     * @return string
     */
    public function getRegistryName()
    {
        return 'craft_keen.revision.registry';
    }

    /**
     * @return string
     */
    public function getServiceTag()
    {
        return 'craft_keen.revision.provider';
    }

    /**
     * @return string
     */
    public function getServiceMethod()
    {
        return 'addProvider';
    }
}
