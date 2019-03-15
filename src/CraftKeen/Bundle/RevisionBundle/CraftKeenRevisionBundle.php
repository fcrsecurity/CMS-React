<?php

namespace CraftKeen\Bundle\RevisionBundle;

use CraftKeen\Bundle\RevisionBundle\DependencyInjection\Compiler\RevisionRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CraftKeenRevisionBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new RevisionRegistryPass());
    }
}
