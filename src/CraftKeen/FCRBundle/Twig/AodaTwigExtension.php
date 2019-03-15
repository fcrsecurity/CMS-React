<?php

namespace CraftKeen\FCRBundle\Twig;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AodaTwigExtension extends \Twig_Extension
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct($container)
    {
        $this->container = $container;
    }

    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('aoda_long_description_url', array($this, 'getAodaLongDescription')),
        );
    }

    /**
     * @param $entityName
     * @param $objectId
     * @param $objectName
     *
     * @return string
     */
    public function getAodaLongDescription($entityName, $objectId, $objectName)
    {
        return $this->container->get('router')
            ->generate("admin_fcr_aoda_long_description_redirect", [
                'class' => strtolower($entityName),
                'id' => $objectId,
                'field' => $objectName
            ], UrlGeneratorInterface::ABSOLUTE_URL);
    }

    public function getName()
    {
        return 'some_helper';
    }

}
