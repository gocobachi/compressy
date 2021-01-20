<?php

namespace Gocobachi\Compressy\Resource\Reader\Stream;

use Gocobachi\Compressy\Resource\Resource as ZippyResource;
use Gocobachi\Compressy\Resource\ResourceReader;
use Gocobachi\Compressy\Resource\ResourceReaderFactory;

class StreamReaderFactory implements ResourceReaderFactory
{
    /**
     * @param ZippyResource $resource
     * @param string        $context
     *
     * @return ResourceReader
     */
    public function getReader(ZippyResource $resource, $context)
    {
        return new StreamReader($resource);
    }
}
