<?php

namespace Gocobachi\Compressy\Tests\Adapter\BSDTar;

use Gocobachi\Compressy\Adapter\BSDTar\TarBz2BSDTarAdapter;

class TarBz2BSDTarAdapterTest extends BSDTarAdapterWithOptionsTest
{
    protected function getOptions()
    {
        return '--bzip2';
    }

    protected static function getAdapterClassName()
    {
        return TarBz2BSDTarAdapter::class;
    }
}
