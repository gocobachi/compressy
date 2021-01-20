<?php

namespace Gocobachi\Compressy\Resource;

use Gocobachi\Compressy\Resource\Resource as ZippyResource;

interface ResourceReaderFactory
{
    /**
     * @param ZippyResource $resource
     * @param string        $context
     *
     * @return ResourceReader
     */
    public function getReader(ZippyResource $resource, $context);
}
