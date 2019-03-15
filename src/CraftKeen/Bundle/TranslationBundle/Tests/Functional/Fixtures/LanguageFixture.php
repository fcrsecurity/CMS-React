<?php

namespace CraftKeen\Bundle\TranslationBundle\Tests\Functional\Fixtures;

use CraftKeen\CMS\AdminBundle\Entity\Language;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;

class LanguageFixture extends AbstractFixture
{
    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager)
    {
        $codes = ['en', 'fr'];

        foreach ($codes as $code) {
            $language = new Language();
            $language->setCode($code);
            $manager->persist($language);
            $this->addReference(sprintf('language_%s', $code), $language);
        }
        $manager->flush();
    }
}
