<?php

namespace Gocobachi\Compressy\Resource\Writer;

use Gocobachi\Compressy\Resource\ResourceReader;
use Gocobachi\Compressy\Resource\ResourceWriter;

class FilesystemWriter implements ResourceWriter
{
    /**
     * @param ResourceReader $reader
     * @param string $target
     */
    public function writeFromReader(ResourceReader $reader, $target)
    {
        $directory = dirname($target);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($target, $reader->getContentsAsStream());
    }
}
