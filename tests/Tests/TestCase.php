<?php

namespace Gocobachi\Compressy\Tests;

use Gocobachi\Compressy\Adapter\AdapterInterface;
use Gocobachi\Compressy\Adapter\Resource\ResourceInterface;
use Gocobachi\Compressy\ProcessBuilder\ProcessBuilderFactoryInterface;
use Gocobachi\Compressy\Resource\PathUtil;
use Gocobachi\Compressy\Resource\ResourceCollection;
use Gocobachi\Compressy\Resource\Resource;
use Gocobachi\Compressy\Adapter\VersionProbe\VersionProbeInterface;
use Gocobachi\Compressy\Resource\ResourceManager;
use Symfony\Component\Process\Process;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    public static function getResourcesPath()
    {
        $dir = __DIR__ . '/../../../resources';

        if (!is_dir($dir)) {
            mkdir($dir);
        }

        return $dir;
    }

    protected function getResourceManagerMock($context = '', $elements = [])
    {
        $elements = array_map(function ($item) {
            return new Resource($item, $item);
        }, $elements);

        $collection = new ResourceCollection($context, $elements, false);

        $manager = $this
            ->getMockBuilder(ResourceManager::class)
            ->disableOriginalConstructor()
            ->getMock();

        $manager->expects($this->any())
            ->method('handle')
            ->will($this->returnValue($collection));

        return $manager;
    }

    protected function getResource($data = null)
    {
        $resource = $this->getMockBuilder(ResourceInterface::class)->getMock();

        if (null !== $data) {
            $resource->expects($this->any())
                ->method('getResource')
                ->will($this->returnValue($data));
        }

        return $resource;
    }

    protected function setProbeIsOk(AdapterInterface $adapter)
    {
        if (!method_exists($adapter, 'setVersionProbe')) {
            $this->fail('Trying to set a probe on an adapter that does not support it');
        }

        $probe = $this->getMockBuilder(VersionProbeInterface::class)->getMock();
        $probe->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue(VersionProbeInterface::PROBE_OK));

        $adapter->setVersionProbe($probe);
    }

    protected function setProbeIsNotOk(AdapterInterface $adapter)
    {
        if (!method_exists($adapter, 'setVersionProbe')) {
            $this->fail('Trying to set a probe on an adapter that does not support it');
        }

        $probe = $this->getMockBuilder(VersionProbeInterface::class)->getMock();
        $probe->expects($this->any())
            ->method('getStatus')
            ->will($this->returnValue(VersionProbeInterface::PROBE_NOTSUPPORTED));

        $adapter->setVersionProbe($probe);
    }

    protected function getMockedProcessBuilderFactory($mockedProcessBuilder, $creations = 1)
    {
        $mockedProcessBuilderFactory =
            $this->getMockBuilder(ProcessBuilderFactoryInterface::class)->getMock();

        $mockedProcessBuilderFactory
            ->expects($this->exactly($creations))
            ->method('create')
            ->will($this->returnValue($mockedProcessBuilder));

        return $mockedProcessBuilderFactory;
    }

    protected function getSuccessFullMockProcess($runs = 1)
    {
        $mockProcess = $this
            ->getMockBuilder(Process::class)
            ->disableOriginalConstructor()
            ->getMock();

        $mockProcess
            ->expects($this->exactly($runs))
            ->method('run');

        $mockProcess
            ->expects($this->exactly($runs))
            ->method('isSuccessful')
            ->will($this->returnValue(true));

        return $mockProcess;
    }

    protected function getExpectedAbsolutePathForTarget($target)
    {
        $directory = dirname($target);

        if (!is_dir($directory)) {
            throw new \InvalidArgumentException(sprintf('Unable to get the absolute path for %s', $target));
        }

        return realpath($directory).'/'.PathUtil::basename($target);
    }
}
