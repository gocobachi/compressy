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

use Gocobachi\Compressy\Adapter\BSDTar\TarBSDTarAdapter;
use Gocobachi\Compressy\Adapter\GNUTar\TarGNUTarAdapter;

class TarFileStrategy extends AbstractFileStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function getServiceNames()
    {
        return [
            TarGNUTarAdapter::class,
            TarBSDTarAdapter::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension()
    {
        return 'tar';
    }
}
