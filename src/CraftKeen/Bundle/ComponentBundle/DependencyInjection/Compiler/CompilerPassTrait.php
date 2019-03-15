<?php

namespace CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

trait CompilerPassTrait
{
    /**
     * @return string
     */
    abstract public function getRegistryName();

    /**
     * @return string
     */
    abstract public function getServiceTag();

    /**
     * @return string
     */
    abstract public function getServiceMethod();

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has($this->getRegistryName())) {
            return;
        }

        $definition = $container->findDefinition($this->getRegistryName());

        $taggedServices = $container->findTaggedServiceIds($this->getServiceTag());

        foreach ($taggedServices as $id => $tags) {
            foreach ($tags as $attributes) {
                $definition->addMethodCall($this->getServiceMethod(), [new Reference($id), $attributes["alias"]]);
            }
        }
    }
}
