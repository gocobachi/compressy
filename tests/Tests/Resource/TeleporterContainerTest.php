<?php

namespace Gocobachi\Compressy\Tests\Resource;

use Gocobachi\Compressy\Exception\InvalidArgumentException;
use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\Resource\TeleporterContainer;

class TeleporterContainerTest extends TestCase
{
    /**
     * @covers \Gocobachi\Compressy\Resource\TeleporterContainer::fromResource
     * @dataProvider provideResourceData
     */
    public function testFromResource($resource, $classname)
    {
        $container = TeleporterContainer::load();

        $this->assertInstanceOf($classname, $container->fromResource($resource));
    }
    /**
     * @covers \Gocobachi\Compressy\Resource\TeleporterContainer::fromResource
     */
    public function testFromResourceThatFails()
    {

        $this->expectException(InvalidArgumentException::class);

        $container = TeleporterContainer::load();
        $container->fromResource($this->createResource(array()));
    }

    public function provideResourceData()
    {
        return array(
            array($this->createResource(__FILE__), 'Gocobachi\Compressy\Resource\Teleporter\LocalTeleporter'),
            array($this->createResource(fopen(__FILE__, 'rb')), 'Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter'),
            array($this->createResource('ftp://192.168.1.1/images/elephant.png'), 'Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter'),
            array($this->createResource('http://127.0.0.1:8080/plus-badge.png'), 'Gocobachi\Compressy\Resource\Teleporter\GenericTeleporter'),
        );
    }

    private function createResource($data)
    {
        $resource = $this->getMockBuilder('\Gocobachi\Compressy\Resource\Resource')
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->any())
            ->method('getOriginal')
            ->will($this->returnValue($data));

        return $resource;
    }

    /**
     * @covers Gocobachi\Compressy\Resource\TeleporterContainer::load
     */
    public function testLoad()
    {
        $container = TeleporterContainer::load();

        $this->assertInstanceOf('Gocobachi\Compressy\Resource\TeleporterContainer', $container);

        $this->assertInstanceOf('Gocobachi\Compressy\Resource\Teleporter\GenericTeleporter', $container['guzzle-teleporter']);
        $this->assertInstanceOf('Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter', $container['stream-teleporter']);
        $this->assertInstanceOf('Gocobachi\Compressy\Resource\Teleporter\LocalTeleporter', $container['local-teleporter']);
    }
}
