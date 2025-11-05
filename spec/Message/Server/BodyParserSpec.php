<?php

declare(strict_types=1);

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
    public function let()
    {
        $this->beConstructedWith('application/json');
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(BodyParser::class);
    }

    public function it_parses_a_given_body_stream()
    {
        $stream = new TextStream(json_encode(['foo' => 'bar']));
        $this->parse($stream)->shouldBeAnInstanceOf(\stdClass::class);
    }
}
