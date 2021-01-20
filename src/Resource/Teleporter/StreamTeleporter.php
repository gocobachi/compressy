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

use Gocobachi\Compressy\Resource\Reader\Stream\StreamReaderFactory;
use Gocobachi\Compressy\Resource\ResourceLocator;
use Gocobachi\Compressy\Resource\Writer\StreamWriter;

/**
 * This class transport an object using php stream wrapper
 */
class StreamTeleporter extends GenericTeleporter
{
    public function __construct()
    {
        parent::__construct(new StreamReaderFactory(), new StreamWriter(), new ResourceLocator());
    }

    /**
     * Creates the StreamTeleporter
     *
     * @return StreamTeleporter
     * @deprecated This method will be removed in a future release (0.5.x)
     */
    public static function create()
    {
        return new static();
    }
}
