<?php

namespace Gocobachi\Compressy\Tests\Adapter\GNUTar;

class TarBz2GNUTarAdapterTest extends GNUTarAdapterWithOptionsTest
{
    protected function getOptions()
    {
        return '--bzip2';
    }

    protected static function getAdapterClassName()
    {
        return 'Gocobachi\\Compressy\\Adapter\\GNUTar\\TarBz2GNUTarAdapter';
    }
}
