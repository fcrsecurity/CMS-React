<?php

namespace CraftKeen\Bundle\TranslationBundle\DataFixtures\ORM;

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
