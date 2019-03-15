<?php

namespace CraftKeen\CMS\AdminBundle\DataFixtures\ORM;

use CraftKeen\Bundle\FixtureBundle\Model\AbstractAliceFixture;
use CraftKeen\Bundle\TranslationBundle\DataFixtures\ORM\LoadLanguageData;
use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LoadSiteData extends AbstractAliceFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $objects = parent::loadFromFile(dirname(__FILE__) . '/sites.yml', $manager);

        /** @var Site $object */
        foreach ($objects as $object) {
            if ($object->getIsMain()) {
                $object->setDomain($this->container->getParameter('base_host'));
                $manager->persist($object);
                $manager->flush($objects);
                break;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDependencies()
    {
        return [LoadLanguageData::class];
    }
}
