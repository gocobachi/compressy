<?php

namespace Gocobachi\Compressy\Functional;

use Gocobachi\Compressy\Adapter\BSDTar\TarBz2BSDTarAdapter;
use Gocobachi\Compressy\Adapter\BSDTar\TarGzBSDTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarBz2GNUTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarGzGNUTarAdapter;
use Gocobachi\Compressy\Archive\ArchiveInterface;
use Gocobachi\Compressy\Exception\NotSupportedException;
use Symfony\Component\Finder\Finder;

class Add2ArchiveTest extends FunctionalTestCase
{
    private static $file;

    public static function tearDownAfterClass(): void
    {
        parent::tearDownAfterClass();

        if (file_exists(self::$file)) {
            unlink(self::$file);
            self::$file = null;
        }
    }

    /**
     * @return ArchiveInterface
     */
    private function create()
    {
        $directory = __DIR__ . '/samples/directory';
        $emptyDirectory = __DIR__ . '/samples/directory/empty';
        $adapter = $this->getAdapter();
        $extension = $this->getArchiveExtensionForAdapter($adapter);

        self::$file = __DIR__ . '/samples/create-archive.' . $extension;

        if (! file_exists($emptyDirectory)) {
            mkdir($emptyDirectory);
        }
        $archive = $adapter->create(self::$file, array(
            'directory' => $directory,
        ), true);

        return $archive;
    }

    public function testAdd()
    {
        $archive = $this->create();

        $target = __DIR__ . '/samples/tmp';
        if (!is_dir($target)) {
            mkdir($target);
        }

        if (in_array(get_class($this->getAdapter()), [
            TarGzGNUTarAdapter::class,
            TarBz2GNUTarAdapter::class,
            TarGzBSDTarAdapter::class,
            TarBz2BSDTarAdapter::class,
        ])) {
            $this->expectException(NotSupportedException::class);
            $this->expectExceptionMessage('Updating a compressed tar archive is not supported.');
        }

        $archive->addMembers(array('somemorefiles/nicephoto.jpg' => __DIR__ . '/samples/morefiles/morephoto.jpg'));
        $archive->extract($target);

        $finder = new Finder();
        $finder
            ->in($target);

        $files2find = array(
            '/directory',
            '/directory/empty',
            '/directory/README.md',
            '/directory/photo.jpg',
            '/somemorefiles',
            '/somemorefiles/nicephoto.jpg',
        );

        foreach ($finder as $file) {
            $this->assertEquals(0, strpos($file->getPathname(), $target));
            $member = substr($file->getPathname(), strlen($target));
            $this->assertContains($member, $files2find, "looking for $member in files2find");
            unset($files2find[array_search($member, $files2find)]);
        }

        $this->assertEquals(array(), $files2find);
    }
}
