<?php

namespace Gocobachi\Compressy\Resource\Writer;

use Gocobachi\Compressy\Resource\ResourceReader;
use Gocobachi\Compressy\Resource\ResourceWriter;

class StreamWriter implements ResourceWriter
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

        $targetResource = fopen($target, 'w+');
        $sourceResource = $reader->getContentsAsStream();

        stream_copy_to_stream($sourceResource, $targetResource);
        fclose($targetResource);
    }
}
