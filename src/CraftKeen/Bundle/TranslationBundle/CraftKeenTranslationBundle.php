<?php

namespace CraftKeen\Bundle\TranslationBundle;

use CraftKeen\Bundle\TranslationBundle\DependencyInjection\Compiler\LanguageProviderAwarePass;
use CraftKeen\Bundle\TranslationBundle\DependencyInjection\Compiler\TranslationRegistryPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class CraftKeenTranslationBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TranslationRegistryPass());
        $container->addCompilerPass(new LanguageProviderAwarePass());
    }
}
