<?php

namespace CraftKeen\Bundle\ComponentBundle;

use CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler\DoctrineAwarePass;
use CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler\SecurityTokenStoragePass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CraftKeenComponentBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new DoctrineAwarePass());
        $container->addCompilerPass(new SecurityTokenStoragePass());
    }
}
