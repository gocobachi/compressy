<?php

namespace Gocobachi\Compressy\Tests\Resource\Teleporter;

use Gocobachi\Compressy\Resource\Teleporter\GuzzleTeleporter;
use Gocobachi\Compressy\Resource\Resource;

class GuzzleTeleporterTest extends TeleporterTestCase
{
    /**
     * @covers Gocobachi\Compressy\Resource\Teleporter\GuzzleTeleporter::teleport
     * @dataProvider provideContexts
     */
    public function testTeleport($context)
    {
        $teleporter = GuzzleTeleporter::create();

        $target = 'plop-badge.png';
        $resource = new Resource('http://127.0.0.1:8080/plus-badge.png', $target);

        if (is_file($context . '/' . $target)) {
            unlink($context . '/' . $target);
        }

        $teleporter->teleport($resource, $context);

        $this->assertFileExists($context . '/' . $target);
        unlink($context . '/' . $target);
    }

    /**
     * @covers Gocobachi\Compressy\Resource\Teleporter\GuzzleTeleporter::create
     */
    public function testCreate()
    {
        $this->assertInstanceOf('Gocobachi\Compressy\Resource\Teleporter\GuzzleTeleporter', GuzzleTeleporter::create());
    }
}
