<?php

namespace CraftKeen\Bundle\ComponentBundle\Tests\Unit\Twig;

use CraftKeen\Bundle\ComponentBundle\Provider\EntityLinkProvider;
use CraftKeen\Bundle\ComponentBundle\Twig\EntityLinkExtension;

class EntityLinkExtensionTest extends \PHPUnit_Framework_TestCase
{
    /** @var \PHPUnit_Framework_MockObject_MockObject|EntityLinkProvider */
    protected $entityLinkProvider;

    /** @var EntityLinkExtension */
    protected $extension;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->entityLinkProvider = $this->createMock(EntityLinkProvider::class);
        $this->extension = new EntityLinkExtension($this->entityLinkProvider);
    }

    public function testGetFilters()
    {
        $this->assertEquals(
            [
                new \Twig_Filter(
                    'entity_slug_link',
                    [$this->entityLinkProvider, 'getSlugLink'],
                    ['is_safe' => ['all']]
                ),
                new \Twig_Filter(
                    'entity_view_link',
                    [$this->entityLinkProvider, 'getViewLink'],
                    ['is_safe' => ['all']]
                ),
            ],
            $this->extension->getFilters()
        );
    }

    public function testGetFunctions()
    {
        $this->assertEquals(
            [],
            $this->extension->getFunctions()
        );
    }
}
