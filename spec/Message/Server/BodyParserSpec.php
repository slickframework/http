<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server;

use Slick\Http\Message\Server\BodyParser;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Stream\TextStream;

/**
 * BodyParserSpec specs
 *
 * @package spec\Slick\Http\Message\Server
 */
class BodyParserSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith('application/json');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(BodyParser::class);
    }

    function it_parses_a_given_body_stream()
    {
        $stream = new TextStream(json_encode(['foo' => 'bar']));
        $this->parse($stream)->shouldBeObject();
    }
}