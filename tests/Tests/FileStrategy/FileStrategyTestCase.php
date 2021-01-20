<?php

namespace Gocobachi\Compressy\Tests\FileStrategy;

use Gocobachi\Compressy\Adapter\AdapterInterface;
use Gocobachi\Compressy\Exception\RuntimeException;
use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\FileStrategy\FileStrategyInterface;

abstract class FileStrategyTestCase extends TestCase
{
    /** @test */
    public function getFileExtensionShouldReturnAnString()
    {
        $that = $this;
        $container = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterContainer')->getMock();
        $container
                ->expects($this->any())
                ->method('offsetGet')
                ->will($this->returnCallback(function ($offset) use ($that) {
                    if (array_key_exists('Gocobachi\Compressy\Adapter\AdapterInterface', class_implements($offset))) {
                        return $that->getMock('Gocobachi\Compressy\Adapter\AdapterInterface');
                    }

                    return null;
                }));

        $extension = $this->getStrategy($container)->getFileExtension();

        $this->assertNotEquals('', trim($extension));
        $this->assertIsString($extension);
    }

    /** @test */
    public function getAdaptersShouldReturnAnArrayOfAdapter()
    {
        $that = $this;
        $container = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterContainer')->getMock();
        $container
                ->expects($this->any())
                ->method('offsetGet')
                ->will($this->returnCallback(function ($offset) use ($that) {
                    if (array_key_exists('Gocobachi\Compressy\Adapter\AdapterInterface', class_implements($offset))) {
                        return $that->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterInterface')->getMock();
                    }

                    return null;
                }));

        $adapters = $this->getStrategy($container)->getAdapters();

        $this->assertIsArray($adapters);

        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\AdapterInterface', $adapter);
        }
    }

    /** @test */
    public function getAdaptersShouldReturnAnArrayOfAdapterEvenIfAdapterRaiseAnException()
    {
        $container = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterContainer')->getMock();
        $container
            ->expects($this->any())
            ->method('offsetGet')
            ->will($this->throwException(new RuntimeException()));

        $adapters = $this->getStrategy($container)->getAdapters();

        $this->assertIsArray($adapters);

        foreach ($adapters as $adapter) {
            $this->assertInstanceOf('Gocobachi\\Compressy\\Adapter\\AdapterInterface', $adapter);
        }
    }

    /**
     * @return FileStrategyInterface
     */
    abstract protected function getStrategy($container);
}
