<?php
/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 * (c) Miguel Gocobachi <mgocobachi@php.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Gocobachi\Compressy\FileStrategy;

use Gocobachi\Compressy\Adapter\BSDTar\TarBz2BSDTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarBz2GNUTarAdapter;

class TarBz2FileStrategy extends AbstractFileStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function getServiceNames()
    {
        return [
            TarBz2GNUTarAdapter::class,
            TarBz2BSDTarAdapter::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension()
    {
        return 'tar.bz2';
    }
}
