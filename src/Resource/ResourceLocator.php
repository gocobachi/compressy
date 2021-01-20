<?php

namespace Gocobachi\Compressy\Resource;

use Gocobachi\Compressy\Resource\Resource AS ZippyResource;

class ResourceLocator
{
    public function mapResourcePath(ZippyResource $resource, $context)
    {
        return rtrim($context, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $resource->getTarget();
    }
}
