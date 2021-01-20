<?php

namespace Gocobachi\Compressy\Functional;

use Gocobachi\Compressy\Adapter\AdapterInterface;
use Gocobachi\Compressy\Adapter\AdapterContainer;
use Gocobachi\Compressy\Adapter\BSDTar\TarBSDTarAdapter;
use Gocobachi\Compressy\Adapter\BSDTar\TarBz2BSDTarAdapter;
use Gocobachi\Compressy\Adapter\BSDTar\TarGzBSDTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarBz2GNUTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarGNUTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarGzGNUTarAdapter;
use Gocobachi\Compressy\Adapter\ZipAdapter;
use Gocobachi\Compressy\Adapter\ZipExtensionAdapter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;

abstract class FunctionalTestCase extends TestCase
{
    public function tearDown(): void
    {
        $filesystem = new Filesystem();
        $filesystem->remove(__DIR__ . '/samples/tmp');

        mkdir(__DIR__ . '/samples/tmp');
    }

    /**
     * @return AdapterInterface
     */
    protected function getAdapter()
    {
        if (false === getenv('COMPRESSY_ADAPTER')) {
            throw new \RuntimeException('COMPRESSY_ADAPTER environment variable is not set');
        }

        $adapter = 'Gocobachi\\Compressy\\Adapter\\' . getenv('COMPRESSY_ADAPTER');

        if (!class_exists($adapter)) {
            throw new \InvalidArgumentException(sprintf('class %s does not exist', $adapter));
        }

        $container = AdapterContainer::load();
        $adapter = $container[$adapter];

        if (!$adapter->isSupported()) {
            $this->markTestSkipped(sprintf('Adapter %s is not supported', $adapter->getName()));
        }

        return $adapter;
    }

    protected function getArchiveFileForAdapter($adapter)
    {
        switch (get_class($adapter)) {
            case ZipAdapter::class:
            case ZipExtensionAdapter::class:
                return __DIR__ . '/samples/archive.zip';
                break;
            case TarGzBSDTarAdapter::class:
            case TarGzGNUTarAdapter::class:
                return __DIR__ . '/samples/archive.tar.gz';
                break;
            case TarBz2BSDTarAdapter::class:
            case TarBz2GNUTarAdapter::class:
                return __DIR__ . '/samples/archive.tar.bz2';
                break;
            case TarBSDTarAdapter::class:
            case TarGNUTarAdapter::class:
                return __DIR__ . '/samples/archive.tar';
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unable to find an archive file for %s', get_class($adapter)));
                break;
        }
    }

    protected function getArchiveExtensionForAdapter($adapter)
    {
        switch (get_class($adapter)) {
            case ZipAdapter::class:
            case ZipExtensionAdapter::class:
                return 'zip';
                break;
            case TarGzBSDTarAdapter::class:
            case TarGzGNUTarAdapter::class:
                return 'tar.gz';
                break;
            case TarBz2BSDTarAdapter::class:
            case TarBz2GNUTarAdapter::class:
                return 'tar.bz2';
                break;
            case TarBSDTarAdapter::class:
            case TarGNUTarAdapter::class:
                return 'tar';
                break;
            default:
                throw new \InvalidArgumentException(sprintf('Unable to find an archive file for %s', get_class($adapter)));
                break;
        }
    }
}
