<?php

namespace Gocobachi\Compressy\Adapter\BSDTar;

use Gocobachi\Compressy\Adapter\Resource\ResourceInterface;
use Gocobachi\Compressy\Exception\NotSupportedException;

class TarGzBSDTarAdapter extends TarBSDTarAdapter
{
    /**
     * @inheritdoc
     */
    protected function doAdd(ResourceInterface $resource, $files, $recursive)
    {
        throw new NotSupportedException('Updating a compressed tar archive is not supported.');
    }

    /**
     * @inheritdoc
     */
    protected function getLocalOptions()
    {
        return array('--gzip');
    }
}
