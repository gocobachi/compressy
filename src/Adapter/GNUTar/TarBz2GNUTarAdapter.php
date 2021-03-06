<?php

namespace Gocobachi\Compressy\Adapter\GNUTar;

use Gocobachi\Compressy\Adapter\Resource\ResourceInterface;
use Gocobachi\Compressy\Exception\NotSupportedException;

class TarBz2GNUTarAdapter extends TarGNUTarAdapter
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
        return array('--bzip2');
    }
}
