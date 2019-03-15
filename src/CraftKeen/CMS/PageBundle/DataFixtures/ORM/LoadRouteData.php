<?php

namespace CraftKeen\CMS\PageBundle\DataFixtures\ORM;

use CraftKeen\Bundle\FixtureBundle\Model\AbstractAliceFixture;
use CraftKeen\Bundle\TranslationBundle\DataFixtures\ORM\LoadLanguageData;
use CraftKeen\CMS\AdminBundle\DataFixtures\ORM\LoadSiteData;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadRouteData extends AbstractAliceFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    use ContainerAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $theme = $this->container->getParameter('theme_name');

        parent::loadFromFile(dirname(__FILE__) . '/Data/' . $theme . '/route.yml', $manager);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [LoadSiteData::class];
    }
}
