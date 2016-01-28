<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Exception\MissingContentException;
use Slick\Http\Exception\ParsingFailureException;

/**
 * Parser Interface
 *
 * @package Slick\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ParserInterface
{

    /**
     * Parses the current content and returns its data
     *
     * @return null|array|object The deserialized data from current content
     *
     * @throws ParsingFailureException If an error occurs when parsing the
     *                                 contents
     * @throws MissingContentException If trying to parse content without
     *                                 setting the content stream
     */
    public function parse();

    /**
     * Sets the content to be parsed
     *
     * @param StreamInterface $content
     *
     * @return self
     */
    public function setContent(StreamInterface $content);
}
