<?php

namespace Gocobachi\Compressy\Tests\Adapter\Resource;

use Gocobachi\Compressy\Tests\TestCase;
use Gocobachi\Compressy\Adapter\Resource\FileResource;

class FileResourceTest extends TestCase
{
    public function testGetResource()
    {
        $path = '/path/to/resource';
        $resource = new FileResource($path);

        $this->assertEquals($path, $resource->getResource());
    }
}
