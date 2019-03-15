<?php

namespace CraftKeen\Bundle\ComponentBundle\Traits;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

trait AwareCompilerPassTrait
{
    /**
     * @return string
     */
    abstract protected function getServiceName();

    /**
     * @return string
     */
    abstract protected function getInterfaceName();

    /**
     * @return string
     */
    abstract protected function getSetterName();

    /**
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $definitions = $container->getDefinitions();
        $reference = new Reference($this->getServiceName());
        $interface = $this->getInterfaceName();

        /** @var Definition $definition */
        foreach ($definitions as $definition) {
            if (!$definition->getClass() || !class_exists($definition->getClass())) {
                continue;
            }
            if (in_array($interface, class_implements($definition->getClass()), true)) {
                $definition->addMethodCall($this->getSetterName(), [$reference]);
            }
        }
    }
}
