<?php

namespace Gocobachi\Compressy\Tests\FileStrategy;

use Gocobachi\Compressy\FileStrategy\TarFileStrategy;

class TarFileStrategyTest extends FileStrategyTestCase
{
    protected function getStrategy($container)
    {
        return new TarFileStrategy($container);
    }
}
