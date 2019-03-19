<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Exception\InvalidArgumentException;
use Slick\Http\Message\Server\BodyParser\JsonParser;
use Slick\Http\Message\Server\BodyParser\NullParser;
use Slick\Http\Message\Server\BodyParser\UrlEncodedParser;
use Slick\Http\Message\Server\BodyParser\XmlParser;

/**
 * BodyParser
 *
 * @package Slick\Http\Message\Server
*/
class BodyParser
{
    /**
     * @var string
     */
    private $contentType;

    /**
     * @var array
     */
    private static $parsers = [
        JsonParser::class => ['+json', 'application/json'],
        XmlParser::class  => ['+xml', 'text/xml'],
        UrlEncodedParser::class => ['urlencoded']
    ];

    /**
     * Creates a Body Parser for provided header
     * @param $contentType
     */
    public function __construct($contentType)
    {
        $this->contentType = $contentType;
    }

    /**
     * Parses provided message body
     *
     * @param StreamInterface $body
     * @return mixed
     */
    public function parse(StreamInterface $body)
    {
        return $this->createParserWith($body)->parse();
    }

    /**
     * Creates the parser based on the parsers map with provided stream
     *
     * @param StreamInterface $body
     *
     * @return BodyParserInterface
     */
    private function createParserWith(StreamInterface $body)
    {
        $class = NullParser::class;
        foreach (self::$parsers as $parser => $contentTypes) {
            foreach ($contentTypes as $contentType) {
                if (stripos($this->contentType, $contentType) !== false) {
                    $class = $parser;
                }
            }
        }
        return new $class($body);
    }

    /**
     * Adds a body parser to parsers map
     *
     * @param string $className
     * @param array  $contentTypes
     *
     * @throws InvalidArgumentException if provided class does not implement BodyParserInterface
     */
    public static function addParser($className, array $contentTypes)
    {
        if (! is_a($className, BodyParserInterface::class)) {
            throw new InvalidArgumentException(
                "Parser objects MUST implement the BodyParserInterface interface."
            );
        }

        $existing = isset(self::$parsers[$className])
            ? self::$parsers[$className]
            : [];
        array_unshift(
            self::$parsers[$className],
            array_merge($existing, $contentTypes)
        );
    }
}
