<?php

namespace CraftKeen\Bundle\WidgetBundle;

use CraftKeen\Bundle\WidgetBundle\DependencyInjection\Compiler\WidgetFactoryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CraftKeenWidgetBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new WidgetFactoryPass());
    }
}
