<?php

namespace CraftKeen\FCRBundle\Tests\Functional\Fixtures;

use CraftKeen\Bundle\TranslationBundle\Tests\Functional\Fixtures\LanguageFixture;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\CMS\AdminBundle\Tests\Functional\Fixtures\SiteFixture;
use CraftKeen\CMS\UserBundle\Tests\Functional\Fixtures\UserFixture;
use CraftKeen\FCRBundle\Entity\CareersEmployee;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class CareersEmployeeFixture extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function getDependencies()
    {
        return [
            LanguageFixture::class,
            SiteFixture::class,
            UserFixture::class,
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $statuses = ['live'];
        /** @var Language $language */
        $language = $this->getReference('language_en');
        $site = $this->getReference('default_site');
        $user = $this->getReference('default_user');

        for ($i = 0; $i < 10; $i++) {
            foreach ($statuses as $status) {
                $entity = new CareersEmployee();
                $entity->setStatus($status)
                    ->setName(uniqid('name_'))
                    ->setPosition(uniqid('position_'))
                    ->setImg(uniqid('img_'))
                    ->setImageAlt(uniqid('image_alt_'))
                    ->setDescription(uniqid('description_'))
                    ->setQuestions(uniqid('questions_'))
                    ->setCreatedBy($user)
                    ->setLang($language)
                    ->setSite($site)//->setContent(uniqid('content_'))
                ;
                $manager->persist($entity);
            }
        }

        $manager->flush();
    }
}
