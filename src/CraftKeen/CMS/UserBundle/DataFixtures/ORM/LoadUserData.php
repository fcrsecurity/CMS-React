<?php

namespace CraftKeen\CMS\UserBundle\DataFixtures\ORM;

use CraftKeen\Bundle\FixtureBundle\Model\AbstractAliceFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadUserData extends AbstractAliceFixture implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $theme = $this->container->getParameter('theme_name');
        parent::loadFromFile(dirname(__FILE__) . '/Data/' . $theme . '/users.yml', $manager);
    }
}
