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
 * XmlParser
 *
 * @package Slick\Http\Message\Server\BodyParser
*/
class XmlParser implements BodyParserInterface
{
    /**
     * @var StreamInterface
     */
    private $stream;

    /**
     * Creates a XML Body Parser
     *
     * @param StreamInterface $stream
     */
    public function __construct(StreamInterface $stream)
    {
        $this->stream = $stream;
    }

    /**
     * Parses the body as a XML document
     *
     * @return \SimpleXMLElement
     */
    public function parse()
    {
        $this->stream->rewind();
        return simplexml_load_string($this->stream->getContents());
    }
}