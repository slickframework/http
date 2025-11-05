<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Stream;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Exception\InvalidArgumentException;

/**
 * File Stream
 *
 * @package Slick\Http\Message\Stream
 */
class FileStream extends AbstractStream implements StreamInterface
{
    /**
     * Creates a File Stream
     *
     * @param string $file The file FQ name to create the stream from
     *
     * @throws InvalidArgumentException If provided file does not exists
     */
    public function __construct($file)
    {
        if (!filter_var($file, FILTER_VALIDATE_URL) && !is_file($file)) {
            throw new InvalidArgumentException(
                "Cannot create stream: given file is not found."
            );
        }

        $string = fopen($file, 'r');
        if ($string === false) {
            throw new InvalidArgumentException(
                "Cannot create stream: could not open file."
            );
        }

        $this->stream = $string;
    }
}
