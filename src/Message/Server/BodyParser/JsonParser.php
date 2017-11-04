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
 * JsonParser
 *
 * @package Slick\Http\Message\Server\BodyParser
*/
class JsonParser implements BodyParserInterface
{
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * Creates JsonParser
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Parses the provided
     *
     * @return mixed
     */
    public function parse()
    {
        $this->stream->rewind();
        return json_decode($this->stream->getContents());
    }
}