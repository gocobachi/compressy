<?php

namespace Gocobachi\Compressy\Tests\Adapter;

use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\Adapter\AdapterContainer;

class AdapterContainerTest extends TestCase
{
    /** @test */
    public function itShouldRegisterAdaptersOnload()
    {
        $container = AdapterContainer::load();

        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\ZipAdapter', $container['Gocobachi\\Compressy\\Adapter\\ZipAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\ZipExtensionAdapter', $container['Gocobachi\\Compressy\\Adapter\\ZipExtensionAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\GNUTar\\TarGNUTarAdapter', $container['Gocobachi\\Compressy\\Adapter\\GNUTar\\TarGNUTarAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\GNUTar\\TarGzGNUTarAdapter', $container['Gocobachi\\Compressy\\Adapter\\GNUTar\\TarGzGNUTarAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\GNUTar\\TarBz2GNUTarAdapter', $container['Gocobachi\\Compressy\\Adapter\\GNUTar\\TarBz2GNUTarAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\BSDTar\\TarGzBSDTarAdapter', $container['Gocobachi\\Compressy\\Adapter\\BSDTar\\TarGzBSDTarAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\BSDTar\\TarBSDTarAdapter', $container['Gocobachi\\Compressy\\Adapter\\BSDTar\\TarBSDTarAdapter']);
        $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\BSDTar\\TarBz2BSDTarAdapter', $container['Gocobachi\\Compressy\\Adapter\\BSDTar\\TarBz2BSDTarAdapter']);
    }
}
