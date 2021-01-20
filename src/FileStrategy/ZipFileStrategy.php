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

use Gocobachi\Compressy\Adapter\ZipAdapter;
use Gocobachi\Compressy\Adapter\ZipExtensionAdapter;

class ZipFileStrategy extends AbstractFileStrategy
{
    /**
     * {@inheritdoc}
     */
    protected function getServiceNames()
    {
        return [
            ZipAdapter::class,
            ZipExtensionAdapter::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFileExtension()
    {
        return 'zip';
    }
}
