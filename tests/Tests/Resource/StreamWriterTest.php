<?php

namespace Gocobachi\Compressy\Tests\Resource;

use Gocobachi\Compressy\Resource\Reader\Stream\StreamReader;
use Gocobachi\Compressy\Resource\Resource;
use Gocobachi\Compressy\Resource\Writer\StreamWriter;
use Gocobachi\Compressy\Tests\TestCase;

class StreamWriterTest extends TestCase
{
    public function testWriteFromReader()
    {
        $this->markTestIncomplete('Needs to be finished');

        $resource = new Resource(fopen(__FILE__, 'r'), fopen(__FILE__, 'r'));
        $reader = new StreamReader($resource);

        $streamWriter = new StreamWriter();

        $streamWriter->writeFromReader($reader, sys_get_temp_dir().'/stream/writer/test.php');
        $streamWriter->writeFromReader($reader, sys_get_temp_dir().'/test.php');
    }
}
