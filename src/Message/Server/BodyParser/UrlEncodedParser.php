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
 * UrlEncodedParser
 *
 * @package Slick\Http\Message\Server\BodyParser
*/
class UrlEncodedParser implements BodyParserInterface
{
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * Creates an URL Encoded Body Parser
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Parses the URL encoded body.
     *
     * @return array
     */
    public function parse()
    {
        $this->stream->rewind();
        parse_str($this->stream->getContents(), $parsed);
        return array_merge($_POST, $parsed);
    }
}
