<?php

namespace Gocobachi\Compressy\Tests\FileStrategy;

use Gocobachi\Compressy\FileStrategy\ZipFileStrategy;

class ZipFileStrategyTest extends FileStrategyTestCase
{
    protected function getStrategy($container)
    {
        return new ZipFileStrategy($container);
    }
}
