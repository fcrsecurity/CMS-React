<?php

namespace CraftKeen\Bundle\ComponentBundle\Tests\Unit\Metadata;

use CraftKeen\Bundle\ComponentBundle\Metadata\EntityConfig;

class EntityConfigTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param array $data
     * @param $require
     * @param $expected
     *
     * @dataProvider dataProvider
     */
    public function testGetRoute(array $data, $require, $expected)
    {
        $object = new EntityConfig($data);
        self::assertEquals($expected, $object->getRoute($require));
    }

    /**
     * @return \Generator
     */
    public function dataProvider()
    {
        yield 'none set' => [
            'data' => [],
            'require' => 'some_route',
            'expected' => null,
        ];
        yield 'positive set' => [
            'data' => ['routes' => ['some_route' => 'some_route_value']],
            'require' => 'some_route',
            'expected' => 'some_route_value',
        ];
        //yield 'none set' => [];
    }
}
