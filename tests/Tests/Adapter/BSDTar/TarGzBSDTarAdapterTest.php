<?php

namespace Gocobachi\Compressy\Tests\Adapter\BSDTar;

class TarGzBSDTarAdapterTest extends BSDTarAdapterWithOptionsTest
{
    protected function getOptions()
    {
        return '--gzip';
    }

    protected static function getAdapterClassName()
    {
        return 'Gocobachi\\Compressy\\Adapter\\BSDTar\\TarGzBSDTarAdapter';
    }
}
