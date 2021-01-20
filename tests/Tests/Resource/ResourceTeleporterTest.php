<?php

namespace Gocobachi\Compressy\Tests\Resource;

use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\Resource\ResourceTeleporter;

class ResourceTeleporterTest extends TestCase
{
    /**
     * @covers Gocobachi\Compressy\Resource\ResourceTeleporter::__construct
     */
    public function testConstruct()
    {
        $container = $this->getMockBuilder('\Gocobachi\Compressy\Resource\TeleporterContainer')
            ->disableOriginalConstructor()
            ->getMock();

        $teleporter = new ResourceTeleporter($container);

        $this->assertInstanceOf(ResourceTeleporter::class, $teleporter);

        return $teleporter;
    }

    /**
     * @covers Gocobachi\Compressy\Resource\ResourceTeleporter::teleport
     */
    public function testTeleport()
    {
        $context = 'supa-context';
        $resource = $this->getMockBuilder('\Gocobachi\Compressy\Resource\Resource')
            ->disableOriginalConstructor()
            ->getMock();

        $container = $this->getMockBuilder('\Gocobachi\Compressy\Resource\TeleporterContainer')
            ->disableOriginalConstructor()
            ->getMock();

        $teleporter = $this->getMockBuilder('\Gocobachi\Compressy\Resource\Teleporter\TeleporterInterface')->getMock();
        $teleporter->expects($this->once())
            ->method('teleport')
            ->with($this->equalTo($resource), $this->equalTo($context));

        $container->expects($this->once())
            ->method('fromResource')
            ->with($this->equalTo($resource))
            ->will($this->returnValue($teleporter));

        $resourceTeleporter = new ResourceTeleporter($container);
        $resourceTeleporter->teleport($context, $resource);
    }

    /**
     * @covers Gocobachi\Compressy\Resource\ResourceTeleporter::create
     */
    public function testCreate()
    {
        $this->assertInstanceOf('Gocobachi\Compressy\Resource\ResourceTeleporter', ResourceTeleporter::create());
    }
}
