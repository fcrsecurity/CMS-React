<?php

namespace CraftKeen\Bundle\TranslationBundle\DependencyInjection\Compiler;

use CraftKeen\Bundle\ComponentBundle\Traits\AwareCompilerPassTrait;
use CraftKeen\Bundle\TranslationBundle\Model\LanguageProviderAwareInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class LanguageProviderAwarePass implements CompilerPassInterface
{
    use AwareCompilerPassTrait;

    /**
     * {@inheritdoc}
     */
    protected function getServiceName()
    {
        return 'craft_keen.translation.provider.language';
    }

    /**
     * {@inheritdoc}
     */
    protected function getInterfaceName()
    {
        return LanguageProviderAwareInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSetterName()
    {
        return 'setLanguageProvider';
    }
}
