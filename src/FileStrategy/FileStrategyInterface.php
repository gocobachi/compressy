<?php

/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gocobachi\Compressy\FileStrategy;

use Gocobachi\Compressy\Adapter\AdapterInterface;

interface FileStrategyInterface
{
    /**
     * Returns an array of adapters that match the strategy
     *
     * @return AdapterInterface[]
     */
    public function getAdapters();

    /**
     * Returns the file extension that match the strategy
     *
     * @return string
     */
    public function getFileExtension();
}
