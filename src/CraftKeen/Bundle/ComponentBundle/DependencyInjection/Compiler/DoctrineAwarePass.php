<?php

namespace CraftKeen\Bundle\ComponentBundle\DependencyInjection\Compiler;

use CraftKeen\Bundle\ComponentBundle\Model\DoctrineAwareInterface;
use CraftKeen\Bundle\ComponentBundle\Traits\AwareCompilerPassTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

class DoctrineAwarePass implements CompilerPassInterface
{
    use AwareCompilerPassTrait;

    /**
     * {@inheritdoc}
     */
    protected function getServiceName()
    {
        return 'doctrine';
    }

    /**
     * {@inheritdoc}
     */
    protected function getInterfaceName()
    {
        return DoctrineAwareInterface::class;
    }

    /**
     * {@inheritdoc}
     */
    protected function getSetterName()
    {
        return 'setRegistry';
    }

}
