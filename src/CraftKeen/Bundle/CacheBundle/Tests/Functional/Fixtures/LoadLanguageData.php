<?php

namespace CraftKeen\Bundle\CacheBundle\Tests\Functional\Fixtures;

use CraftKeen\Bundle\FixtureBundle\Model\AbstractAliceFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LoadLanguageData extends AbstractAliceFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadFromFile(
            implode(DIRECTORY_SEPARATOR, [dirname(__FILE__), 'Data', 'language_fixture.yml']),
            $manager
        );
    }
}
