<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Stream;

use Psr\Http\Message\StreamInterface;

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
        $this->stream = fopen('php://memory', 'rw+');
        fputs($this->stream, $content);
    }
}
