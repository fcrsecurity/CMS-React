<?php

namespace CraftKeen\CMS\AdminBundle\Tests\Functional\Fixtures;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class SiteFixture extends AbstractFixture
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $site = new Site();
        $site->setDomain('localhost')
            ->setIsMain(true)
            ->setName('localhost')
            ->setProtocol('https')
            ->setDescription('localhost')
            ->setTheme('FCR');
        $manager->persist($site);
        $this->addReference('default_site', $site);

        $manager->flush();
    }

}
