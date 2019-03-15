<?php

namespace CraftKeen\FCRBundle\Tests\Functional\Repository;

use Akuma\Component\Testing\TestCase\WebTestCase;
use CraftKeen\CMS\AdminBundle\Entity\Language;
use CraftKeen\FCRBundle\Entity\CareersEmployee;
use CraftKeen\FCRBundle\Repository\CareersEmployeeRepository;
use CraftKeen\FCRBundle\Tests\Functional\Fixtures\CareersEmployeeFixture;

class CareersEmployeeRepositoryTest extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        $this->initClient();
        $this->loadFixtures([CareersEmployeeFixture::class]);
    }

    public function testFindRandom()
    {
        $language = $this->getContainer()->get('doctrine')->getRepository(Language::class)->findOneBy(['code' => 'en']);
        /** @var CareersEmployeeRepository $repo */
        $repo = $this->getContainer()->get('doctrine')->getRepository(CareersEmployee::class);
        $items = $repo->findRand($language);

        $this->assertCount(4, $items);
        foreach ($items as $item) {
            $this->assertInstanceOf(CareersEmployee::class, $item);
        }
    }
}
