<?php

namespace CraftKeen\CMS\PageBundle\DataFixtures\ORM;

use CraftKeen\Bundle\FixtureBundle\Model\AbstractAliceFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadWidgetData extends AbstractAliceFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $theme = $this->container->getParameter('theme_name');

        parent::loadFromFile(dirname(__FILE__) . '/Data/' . $theme . '/widget.yml', $manager);
    }
}
