<?php

namespace CraftKeen\Bundle\CacheBundle\Tests\Functional;

use Akuma\Component\Testing\TestCase\WebTestCase;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use Doctrine\ORM\EntityManager;

class CacheChainTest extends WebTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->initClient();
        $this->loadFixtures([Fixtures\LoadLanguageData::class]);
    }

    public function testSampleOfFunctionalTestWithFixture()
    {
        $doctrine = $this->getContainer()->get('doctrine');
        $this->assertNotNull($doctrine);
        /** @var EntityManager $em */
        $em = $doctrine->getManagerForClass(Language::class);
        $lang = new Language();
        $lang->setCode('jp');
        $em->persist($lang);
        $em->flush($lang);
        $x = $em->getRepository(Language::class)->findOneBy(['code' => $lang->getCode()]);
        $this->assertNotNull($x);
        $this->assertEquals($lang, $x);
    }
}
