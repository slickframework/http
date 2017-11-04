<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server\BodyParser;

use Slick\Http\Message\Server\BodyParser\JsonParser;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Server\BodyParserInterface;
use Slick\Http\Message\Stream\TextStream;

/**
 * JsonParserSpec specs
 *
 * @package spec\Slick\Http\Message\Server\BodyParser
 */
class JsonParserSpec extends ObjectBehavior
{

    function let()
    {
        $stream = new TextStream(json_encode(['foo' => 'bar']));
        $this->beConstructedWith($stream);
    }

    function its_a_body_parser()
    {
        $this->shouldBeAnInstanceOf(BodyParserInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(JsonParser::class);
    }

    function it_parses_the_a_json_serialized_body()
    {
        $this->parse()->shouldBeObject();
    }
}