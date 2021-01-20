<?php

namespace Gocobachi\Compressy\Tests;

use Gocobachi\Compressy\Compressy;
use Gocobachi\Compressy\Exception\NoAdapterOnPlatformException;
use Gocobachi\Compressy\Exception\FormatNotSupportedException;
use Gocobachi\Compressy\Exception\RuntimeException;

class CompressyTest extends TestCase
{
    /** @test */
    public function itShouldCreateAnArchive()
    {
        $filename = 'file.zippo';
        $fileToAdd = 'file1';
        $recursive = true;

        $adapter = $this->getSupportedAdapter();

        $adapter->expects($this->once())
            ->method('create')
            ->with($this->equalTo($filename), $this->equalTo($fileToAdd), $this->equalTo($recursive));

        $adapters = array($adapter);
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        $zippy->create($filename, $fileToAdd, $recursive);
    }

    /** @test */
    public function itShouldCreateAnArchiveByForcingType()
    {
        $filename = 'file';
        $fileToAdd = 'file1';
        $recursive = true;

        $adapter = $this->getSupportedAdapter();

        $adapter->expects($this->once())
            ->method('create')
            ->with($this->equalTo($filename), $this->equalTo($fileToAdd), $this->equalTo($recursive));

        $adapters = array($adapter);
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        $zippy->create($filename, $fileToAdd, $recursive, 'zippo');
    }

    /** @test */
    public function itShouldNotCreateAndThrowAnException()
    {
        $filename = 'file';
        $fileToAdd = 'file1';
        $recursive = true;

        $adapter = $this->getSupportedAdapter();

        $adapter->expects($this->never())->method('create');

        $adapters = array($adapter);
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        try {
            $zippy->create($filename, $fileToAdd, $recursive, 'zippotte');
            $this->fail('Should have raised an exception');
        } catch (RuntimeException $e) {

        }
    }

    /** @test */
    public function itShouldOpenAnArchive()
    {
        $filename = 'file.zippo';

        $adapter = $this->getSupportedAdapter();

        $adapter->expects($this->once())
            ->method('open')
            ->with($this->equalTo($filename));

        $adapters = array($adapter);
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        $zippy->open($filename);
    }

    /** @test */
    public function itShouldExposeContainerPassedOnConstructor()
    {
        $container = $this->getContainer();

        $zippy = new Compressy($container);

        $this->assertEquals($container, $zippy->adapters);
    }

    /** @test */
    public function itShouldRegisterStrategies()
    {
        $adapters = array($this->getSupportedAdapter());
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        $this->assertEquals(array('zippo' => array($strategy)), $zippy->getStrategies());
    }

    /** @test */
    public function registerTwoStrategiesWithSameExtensionShouldBeinRightOrder()
    {
        $adapters1 = array($this->getSupportedAdapter());
        $strategy1 = $this->getStrategy('zippo', $adapters1);

        $adapters2 = array($this->getSupportedAdapter());
        $strategy2 = $this->getStrategy('zippo', $adapters2);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy1);
        $zippy->addStrategy($strategy2);

        $this->assertEquals(array('zippo' => array($strategy2, $strategy1)), $zippy->getStrategies());
    }

    /** @test */
    public function registerAStrategyTwiceShouldMoveItToLastAdded()
    {
        $adapters1 = array($this->getSupportedAdapter());
        $strategy1 = $this->getStrategy('zippo', $adapters1);

        $adapters2 = array($this->getSupportedAdapter());
        $strategy2 = $this->getStrategy('zippo', $adapters2);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy1);
        $zippy->addStrategy($strategy2);
        $zippy->addStrategy($strategy1);

        $this->assertEquals(array('zippo' => array($strategy1, $strategy2)), $zippy->getStrategies());
    }

    /** @test */
    public function itShouldReturnAnAdapterCorrespondingToTheRightStrategy()
    {
        $adapters = array($this->getSupportedAdapter());
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        $this->assertEquals($adapters[0], $zippy->getAdapterFor('zippo'));
        $this->assertEquals($adapters[0], $zippy->getAdapterFor('.zippo'));
        $this->assertEquals($adapters[0], $zippy->getAdapterFor('ziPPo'));
        $this->assertEquals($adapters[0], $zippy->getAdapterFor('.ZIPPO'));
    }

    /** @test */
    public function itShouldThrowAnExceptionIfNoAdapterSupported()
    {
        $this->expectException(NoAdapterOnPlatformException::class);

        $adapters = array($this->getNotSupportedAdapter());
        $strategy = $this->getStrategy('zippo', $adapters);

        $zippy = new Compressy($this->getContainer());
        $zippy->addStrategy($strategy);

        $zippy->getAdapterFor('zippo');
    }

    /** @test */
    public function itShouldThrowAnExceptionIfFormatNotSupported()
    {
        $this->expectException(FormatNotSupportedException::class);

        $adapters = array($this->getSupportedAdapter());
        $strategy = $this->getStrategy('zippotte', $adapters);

        $zippy = new Compressy($this->getContainer());

        $zippy->addStrategy($strategy);
        $zippy->getAdapterFor('zippo');
    }

    /** @test */
    public function loadShouldRegisterStrategies()
    {
        $zippy = Compressy::load();

        $this->assertCount(7, $zippy->getStrategies());

        $this->assertArrayHasKey('zip', $zippy->getStrategies());
        $this->assertArrayHasKey('tar', $zippy->getStrategies());
        $this->assertArrayHasKey('tar.gz', $zippy->getStrategies());
        $this->assertArrayHasKey('tar.bz2', $zippy->getStrategies());
        $this->assertArrayHasKey('tbz2', $zippy->getStrategies());
        $this->assertArrayHasKey('tb2', $zippy->getStrategies());
        $this->assertArrayHasKey('tgz', $zippy->getStrategies());
    }

    private function getStrategy($extension, $adapters)
    {
        $strategy = $this->getMockBuilder('\Gocobachi\Compressy\FileStrategy\FileStrategyInterface')->getMock();

        $strategy->expects($this->any())
            ->method('getFileExtension')
            ->will($this->returnValue($extension));

        $strategy->expects($this->any())
            ->method('getAdapters')
            ->will($this->returnValue($adapters));

        return $strategy;
    }

    private function getSupportedAdapter()
    {
        $adapter = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterInterface')->getMock();
        $adapter->expects($this->any())
            ->method('isSupported')
            ->will($this->returnValue(true));

        return $adapter;
    }

    private function getNotSupportedAdapter()
    {
        $adapter = $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterInterface')->getMock();
        $adapter->expects($this->any())
            ->method('isSupported')
            ->will($this->returnValue(false));

        return $adapter;
    }

    private function getContainer()
    {
        return $this->getMockBuilder('\Gocobachi\Compressy\Adapter\AdapterContainer')->getMock();
    }
}
