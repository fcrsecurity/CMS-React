<?php

namespace CraftKeen\Bundle\FixtureBundle\Model;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Nelmio\Alice\Fixtures;

abstract class AbstractAliceFixture extends AbstractFixture
{
    /**
     * @param string $file
     * @param ObjectManager $manager
     *
     * @return mixed
     */
    protected function loadFromFile($file, $manager)
    {
        return Fixtures::load($file, $manager);
    }
}
