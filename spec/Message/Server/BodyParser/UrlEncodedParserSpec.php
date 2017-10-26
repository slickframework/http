<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server\BodyParser;

use Slick\Http\Message\Server\BodyParser\UrlEncodedParser;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Server\BodyParserInterface;
use Slick\Http\Message\Stream\TextStream;

/**
 * UrlEncodedParserSpec specs
 *
 * @package spec\Slick\Http\Message\Server\BodyParser
 */
class UrlEncodedParserSpec extends ObjectBehavior
{

    function let()
    {
        $stream = new TextStream('foo=bar&bar=baz');
        $_POST['foo'] = '1';
        $_POST['test'] = '1';
        $this->beConstructedWith($stream);
    }

    function its_a_body_parser()
    {
        $this->shouldBeAnInstanceOf(BodyParserInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(UrlEncodedParser::class);
    }

    function it_parses_the_url_encoded_data()
    {
        $this->parse()->shouldBe([
            'foo' => 'bar',
            'test' => '1',
            'bar' => 'baz'
        ]);
    }
}