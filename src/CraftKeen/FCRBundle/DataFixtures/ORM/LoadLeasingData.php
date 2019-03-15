<?php

namespace CraftKeen\CMS\FCRBundle\DataFixtures\ORM;

use Symfony\Component\Finder\Finder;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadLeasingData extends AbstractFixture implements ContainerAwareInterface, OrderedFixtureInterface
{	
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
		// TODO: Figured out Language ID from generated fixture.
//		$finder = new Finder();
//		$finder->in(__DIR__.'/Data');
//		$finder->name('leasing_tables.sql');
//
//		foreach( $finder as $file ){
//			$content = $file->getContents();
//
//			$stmt = $this->container->get('doctrine.orm.entity_manager')->getConnection()->prepare($content);
//			$stmt->execute();
//		}
    }
	
	public function setContainer(ContainerInterface $container = null)
	{
		$this->container = $container;
	}
	
	public function getOrder()
	{
		// the order in which fixtures will be loaded
		// the lower the number, the sooner that this fixture is loaded
		return 50;
	}
}
