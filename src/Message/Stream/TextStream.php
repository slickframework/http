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
 * TextStream
 *
 * @package Slick\Http\Message\Stream
*/
class TextStream extends AbstractStream implements StreamInterface
{
    /**
     * Creates a Text Stream
     *
     * @param string $content
     */
    public function __construct($content)
    {
        $stream = fopen('php://memory', 'rw+');
        if (\is_resource($stream)) {
            fputs($stream, $content);
            $this->stream = $stream;
            return;
        }

        throw new InvalidArgumentException(
            "Could not create stream from content: $content"
        );
    }
}
