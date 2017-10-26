<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server\BodyParser;

use Slick\Http\Message\Server\BodyParser\NullParser;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Server\BodyParserInterface;
use Slick\Http\Message\Stream\TextStream;

/**
 * NullParserSpec specs
 *
 * @package spec\Slick\Http\Message\Server\BodyParser
 */
class NullParserSpec extends ObjectBehavior
{

    function let()
    {
        $stream = new TextStream('Hello world');
        $this->beConstructedWith($stream);
    }

    function its_a_body_parser()
    {
        $this->shouldBeAnInstanceOf(BodyParserInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NullParser::class);
    }

    function it_returns_the_body_as_text()
    {
        $this->parse()->shouldBe('Hello world');
    }
}