<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server\BodyParser;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Server\BodyParserInterface;

/**
 * NullParser
 *
 * @package Slick\Http\Message\Server\BodyParser
*/
class NullParser implements BodyParserInterface
{
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * Creates NullParser
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Returns the body stream as text
     *
     * @return string
     */
    public function parse()
    {
        $this->stream->rewind();
        return $this->stream->getContents();
    }
}