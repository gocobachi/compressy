<?php

/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Gocobachi\Compressy\Adapter\VersionProbe;

class ZipExtensionVersionProbe implements VersionProbeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getStatus()
    {
        return class_exists('\ZipArchive') ? VersionProbeInterface::PROBE_OK : VersionProbeInterface::PROBE_NOTSUPPORTED;
    }
}
