<?php

namespace Gocobachi\Compressy\Tests\Resource\Teleporter;

use Gocobachi\Compressy\Resource\Resource;
use Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter;

class StreamTeleporterTest extends TeleporterTestCase
{
    /**
     * @covers Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter::teleport
     * @dataProvider provideContexts
     */
    public function testTeleport($context)
    {
        $teleporter = StreamTeleporter::create();

        $target = 'plop-badge.php';
        $resource = new Resource(fopen(__FILE__, 'rb'), $target);

        if (is_file($context . '/' . $target)) {
            unlink($context . '/' . $target);
        }

        $teleporter->teleport($resource, $context);

        $this->assertFileExists($context . '/' . $target);
        unlink($context . '/' . $target);
    }

    /**
     * @covers Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter::teleport
     * @dataProvider provideContexts
     */
    public function testTeleportInNonStreamMode($context)
    {
        $teleporter = StreamTeleporter::create();

        $target = 'plop-badge.php';
        $resource = new Resource(__FILE__, $target);

        if (is_file($context . '/' . $target)) {
            unlink($context . '/' . $target);
        }

        $teleporter->teleport($resource, $context);

        $this->assertFileExists($context . '/' . $target);
        unlink($context . '/' . $target);
    }

    /**
     * @covers Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter::create
     */
    public function testCreate()
    {
        $this->assertInstanceOf('Gocobachi\Compressy\Resource\Teleporter\StreamTeleporter', StreamTeleporter::create());
    }
}
