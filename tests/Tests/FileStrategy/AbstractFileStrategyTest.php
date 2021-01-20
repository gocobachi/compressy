<?php

namespace Gocobachi\Compressy\Tests\FileStrategy;

use Gocobachi\Compressy\Adapter\AdapterContainer;
use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\Exception\RuntimeException;

class AbstractFileStrategyTest extends TestCase
{
    public function testGetAdaptersWithNoDefinedServices()
    {
        $this->expectException(\InvalidArgumentException::class);

        $container = AdapterContainer::load();

        $stub = $this->getMockForAbstractClass('Gocobachi\Compressy\FileStrategy\AbstractFileStrategy', array($container));
        $stub->expects($this->any())
            ->method('getServiceNames')
            ->will($this->returnValue(array(
                'Unknown\Services'
            )));


        $adapters = $stub->getAdapters();

        $this->assertIsArray($adapters);
        $this->assertCount(0, $adapters);
    }

    public function testGetAdapters()
    {
        $container = AdapterContainer::load();

        $stub = $this->getMockForAbstractClass('Gocobachi\Compressy\FileStrategy\AbstractFileStrategy', array($container));
        $stub->expects($this->any())
            ->method('getServiceNames')
            ->will($this->returnValue(array(
                'Gocobachi\\Compressy\\Adapter\\ZipAdapter',
                'Gocobachi\\Compressy\\Adapter\\ZipExtensionAdapter'
            )));

        $adapters = $stub->getAdapters();

        $this->assertIsArray($adapters);
        $this->assertCount(2, $adapters);

        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\AdapterInterface', $adapter);
        }
    }

    public function testGetAdaptersWithAdapterThatRaiseAnException()
    {
        $adapterMock = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterInterface')->getMock();
        $container = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterContainer')->getMock();
        $container
            ->expects($this->at(0))
            ->method('offsetGet')
            ->with($this->equalTo('Gocobachi\\Compressy\\Adapter\\ZipAdapter'))
            ->will($this->returnValue($adapterMock));

        $container
            ->expects($this->at(1))
            ->method('offsetGet')
            ->with($this->equalTo('Gocobachi\\Compressy\\Adapter\\ZipExtensionAdapter'))
            ->will($this->throwException(new RuntimeException()));

        $stub = $this->getMockForAbstractClass('Gocobachi\Compressy\FileStrategy\AbstractFileStrategy', array($container));
        $stub->expects($this->any())
            ->method('getServiceNames')
            ->will($this->returnValue(array(
                'Gocobachi\\Compressy\\Adapter\\ZipAdapter',
                'Gocobachi\\Compressy\\Adapter\\ZipExtensionAdapter'
            )));

        $adapters = $stub->getAdapters();

        $this->assertIsArray($adapters);
        $this->assertCount(1, $adapters);

        foreach ($adapters as $adapter) {
            $this->assertSame($adapterMock, $adapter);
        }
    }   
}
