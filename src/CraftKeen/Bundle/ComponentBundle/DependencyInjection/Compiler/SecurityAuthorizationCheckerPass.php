<?php

namespace CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler;

use CraftKeen\Bundle\ComponentBundle\Model\SecurityContextAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\AwareCompilerPassTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class SecurityAuthorizationCheckerPass implements CompilerPassInterface
{
    use AwareCompilerPassTrait;

    /**
     * {@inheritdoc}
     */
    protected function getServiceName()
    {
        return 'security.authorization_checker';
    }

    /**
     * {@inheritdoc}
     */
    protected function getInterfaceName()
    {
        return SecurityContextAwareInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSetterName()
    {
        return 'setAuthorizationChecker';
    }
}
