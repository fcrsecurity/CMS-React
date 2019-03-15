<?php

namespace CraftKeen\CMS\PageBundle\DataFixtures\ORM;

use CraftKeen\Bundle\FixtureBundle\Model\AbstractAliceFixture;
use CraftKeen\Bundle\TranslationBundle\DataFixtures\ORM\LoadLanguageData;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadPeopleData extends AbstractAliceFixture implements ContainerAwareInterface, DependentFixtureInterface
{
    use ContainerAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        parent::loadFromFile(dirname(__FILE__) . '/Data/people.yml', $manager);
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [LoadLanguageData::class];
    }
}
