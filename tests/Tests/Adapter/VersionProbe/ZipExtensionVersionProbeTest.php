<?php

namespace Gocobachi\Compressy\Tests\Adapter\VersionProbe;

use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\Adapter\VersionProbe\ZipExtensionVersionProbe;
use Gocobachi\Compressy\Adapter\VersionProbe\VersionProbeInterface;

class ZipExtensionVersionProbeTest extends TestCase
{
    /**
     * @covers Gocobachi\Compressy\Adapter\VersionProbe\ZipExtensionVersionProbe::getStatus
     */
    public function testGetStatus()
    {
        $expectation = VersionProbeInterface::PROBE_OK;
        if (false === class_exists('ZipArchive')) {
            $expectation = VersionProbeInterface::PROBE_NOTSUPPORTED;
        }

        $probe = new ZipExtensionVersionProbe();
        $this->assertEquals($expectation, $probe->getStatus());
    }
}
