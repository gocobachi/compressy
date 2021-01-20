<?php

/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gocobachi\Compressy\Resource\Teleporter;

use Gocobachi\Compressy\Resource\Reader\Guzzle\LegacyGuzzleReaderFactory;
use Gocobachi\Compressy\Resource\ResourceLocator;
use Gocobachi\Compressy\Resource\ResourceReaderFactory;
use Gocobachi\Compressy\Resource\Writer\FilesystemWriter;
use Guzzle\Http\Client;

/**
 * Guzzle Teleporter implementation for HTTP resources
 *
 * @deprecated Use \Alchemy\Compressy\Resource\GenericTeleporter instead. This class will be removed in v0.5.x
 */
class LegacyGuzzleTeleporter extends GenericTeleporter
{
    /**
     * @param Client $client
     * @param ResourceReaderFactory $readerFactory
     * @param ResourceLocator $resourceLocator
     */
    public function __construct(
        Client $client = null,
        ResourceReaderFactory $readerFactory = null,
        ResourceLocator $resourceLocator = null
    ) {
        parent::__construct($readerFactory ?: new LegacyGuzzleReaderFactory($client), new FilesystemWriter(),
            $resourceLocator);
    }

    /**
     * Creates the GuzzleTeleporter
     *
     * @deprecated
     * @return LegacyGuzzleTeleporter
     */
    public static function create()
    {
        return new static();
    }
}
