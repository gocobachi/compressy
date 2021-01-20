<?php

namespace Gocobachi\Compressy\Tests\Adapter;

use Gocobachi\Compressy\Adapter\ZipExtensionAdapter;
use Gocobachi\Compressy\Adapter\Resource\ZipArchiveResource;
use Gocobachi\Compressy\Archive\ArchiveInterface;
use Gocobachi\Compressy\Exception\InvalidArgumentException;
use Gocobachi\Compressy\Exception\NotSupportedException;
use Gocobachi\Compressy\Exception\RuntimeException;

class ZipExtensionAdapterTest extends AdapterTestCase
{
    /**
     * @var ZipExtensionAdapter
     */
    private $adapter;

    public function setUp(): void
    {
        $this->adapter = $this->provideSupportedAdapter();
    }

    public function testNewInstance()
    {
        $adapter = ZipExtensionAdapter::newInstance();

        $this->assertInstanceOf(ZipExtensionAdapter::class, $adapter);
    }

    protected function provideSupportedAdapter()
    {
        $adapter = new ZipExtensionAdapter($this->getResourceManagerMock());
        $this->setProbeIsOk($adapter);

        return $adapter;
    }

    protected function provideNotSupportedAdapter()
    {
        $adapter = new ZipExtensionAdapter($this->getResourceManagerMock());
        $this->setProbeIsNotOk($adapter);

        return $adapter;
    }

    public function testCreateNoFiles()
    {
        $this->expectException(NotSupportedException::class);

        $this->adapter->create(__DIR__ . '/zip-file.zip', array());
    }

    public function testCreate()
    {
        $file = __DIR__ . '/zip-file.zip';
        $manager = $this->getResourceManagerMock(__DIR__, array(__FILE__));
        $this->adapter = new ZipExtensionAdapter($manager);
        $this->setProbeIsOk($this->adapter);
        $archive = $this->adapter->create($file, array(__FILE__));
        $this->assertInstanceOf(ArchiveInterface::class, $archive);
        $this->assertFileExists($file);
        unlink($file);
    }

    public function testOpenWithWrongFileName()
    {
        $file = __DIR__ . '/zip-file.zip';

        $this->expectException(RuntimeException::class);

        $this->adapter->open($file);
    }

    public function testOpenEmptyFile()
    {
        $file = __DIR__ . '/zip-file.zip';

        touch($file);

        // For PHP 8 the zlib extensions throws an exception for empty zip files
        if (PHP_VERSION >= 8) {
            $this->expectException(RuntimeException::class);
        }

        $archive = $this->adapter->open($file);

        $this->assertInstanceOf(ArchiveInterface::class, $archive);

        unlink($file);
    }

    public function testGetName()
    {
        $this->assertIsString($this->adapter->getName());
    }

    public function testListMembers()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $members = $this->adapter->listMembers(new ZipArchiveResource($resource));

        $this->assertIsArray($members);
    }

    public function testExtract()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->once())
            ->method('extractTo')
            ->with($this->equalTo(__DIR__), $this->anything())
            ->will($this->returnValue(true));

        $this->adapter->extract(new ZipArchiveResource($resource), __DIR__);
    }

    public function testExtractOnError()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->once())
            ->method('extractTo')
            ->with($this->equalTo(__DIR__), $this->anything())
            ->will($this->returnValue(false));

        $this->expectException(InvalidArgumentException::class);

        $this->adapter->extract(new ZipArchiveResource($resource), __DIR__);
    }

    public function testExtractWithInvalidTarget()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->expectException(InvalidArgumentException::class);

        $this->adapter->extract(new ZipArchiveResource($resource), __DIR__ . '/boursin');
    }

    public function testExtractWithInvalidTarget2()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $this->expectException(InvalidArgumentException::class);

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->adapter->extract(new ZipArchiveResource($resource));
    }

    public function testRemove()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $files = array(
            'one-file.jpg',
            'second-file.jpg',
        );

        $resource->expects($this->exactly(2))
            ->method('locateName')
            ->will($this->returnValue(true));

        $resource->expects($this->exactly(2))
            ->method('deleteName')
            ->will($this->returnValue(true));

        $this->adapter->remove(new ZipArchiveResource($resource), $files);
    }

    public function testRemoveWithLocateFailing()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $files = array(
            'one-file.jpg'
        );

        $resource->expects($this->once())
            ->method('locateName')
            ->with($this->equalTo('one-file.jpg'))
            ->will($this->returnValue(false));

        $this->expectException(InvalidArgumentException::class);

        $this->adapter->remove(new ZipArchiveResource($resource), $files);
    }

    public function testRemoveWithDeleteFailing()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $files = array(
            'one-file.jpg'
        );

        $resource->expects($this->once())
            ->method('locateName')
            ->with($this->equalTo('one-file.jpg'))
            ->will($this->returnValue(true));

        $resource->expects($this->once())
            ->method('deleteName')
            ->with($this->equalTo('one-file.jpg'))
            ->will($this->returnValue(false));

        $this->expectException(RuntimeException::class);

        $this->adapter->remove(new ZipArchiveResource($resource), $files);
    }

    public function testAdd()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->once())
            ->method('addFile')
            ->will($this->returnValue(true));

        $resource->expects($this->once())
            ->method('addEmptyDir')
            ->will($this->returnValue(true));

        $dir = __DIR__ . '/temp-dir';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $files = array(
            __FILE__,
            $dir,
        );

        $manager = $this->getResourceManagerMock(__DIR__, $files);
        $this->adapter = new ZipExtensionAdapter($manager);
        $this->setProbeIsOk($this->adapter);
        $this->adapter->add(new ZipArchiveResource($resource), $files);

        rmdir($dir);
    }

    public function testAddFailOnFile()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->once())
            ->method('addFile')
            ->will($this->returnValue(false));

        $dir = __DIR__ . '/temp-dir';
        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $files = array(
            __FILE__,
            $dir,
        );

        $this->expectException(RuntimeException::class);

        $manager = $this->getResourceManagerMock(__DIR__, $files);
        $this->adapter = new ZipExtensionAdapter($manager);
        $this->setProbeIsOk($this->adapter);
        $this->adapter->add(new ZipArchiveResource($resource), $files);
    }

    public function testAddFailOnDir()
    {
        $this->markTestIncomplete('A ParseError exceptions happens with the ZipArchive when mocking it');

        $resource = $this->getMockBuilder(\ZipArchive::class)
            ->disableOriginalConstructor()
            ->getMock();

        $resource->expects($this->once())
            ->method('addFile')
            ->will($this->returnValue(true));

        $resource->expects($this->once())
            ->method('addEmptyDir')
            ->will($this->returnValue(false));


        $dir = __DIR__ . '/temp-dir';

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        $files = [
            __FILE__,
            $dir,
        ];

        $manager = $this->getResourceManagerMock(__DIR__, $files);

        $this->adapter = new ZipExtensionAdapter($manager);

        $this->setProbeIsOk($this->adapter);

        $this->expectException(RuntimeException::class);

        $this->adapter->add(new ZipArchiveResource($resource), $files);
    }
}
