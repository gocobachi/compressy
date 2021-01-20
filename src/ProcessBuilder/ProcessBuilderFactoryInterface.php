<?php

/*
 * This file is part of Compressy.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Gocobachi\Compressy\ProcessBuilder;

use Gocobachi\Compressy\Exception\InvalidArgumentException;

interface ProcessBuilderFactoryInterface
{
    /**
     * Returns a new instance of Symfony ProcessBuilder
     *
     * @return ProcessBuilder
     *
     * @throws InvalidArgumentException
     */
    public function create();

    /**
     * Returns the binary path
     *
     * @return string
     */
    public function getBinary();

    /**
     * Sets the binary path
     *
     * @param string $binary A binary path
     *
     * @return ProcessBuilderFactoryInterface
     *
     * @throws InvalidArgumentException In case binary is not executable
     */
    public function useBinary($binary);
}
