<?php

namespace Gocobachi\Compressy\Tests\Adapter\GNUTar;

class TarGzGNUTarAdapterTest extends GNUTarAdapterWithOptionsTest
{
    protected function getOptions()
    {
        return '--gzip';
    }

    protected static function getAdapterClassName()
    {
        return 'Gocobachi\\Compressy\\Adapter\\GNUTar\\TarGzGNUTarAdapter';
    }
}
