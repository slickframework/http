<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Response;
use PhpSpec\ObjectBehavior;

/**
 * ResponseSpec specs
 *
 * @package spec\Slick\Http\Message
 */
class ResponseSpec extends ObjectBehavior
{
    public function let(StreamInterface $body)
    {
        $body->rewind()->shouldBeCalled();
        $body->__toString()->willReturn('<xml>hello world!</xml>');
        $body->getContents()->willReturn('<xml>hello world!</xml>');
        $this->beConstructedWith('200', $body, ['Content-Type' => 'text/xml']);
    }

    public function its_an_http_response()
    {
        $this->shouldHaveType(ResponseInterface::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Response::class);
    }

    public function it_has_a_status_code()
    {
        $this->getStatusCode()->shouldBe(200);
        $this->getReasonPhrase()->shouldBe('OK');
    }

    public function it_can_create_a_response_with_a_new_status_code()
    {
        $response = $this->withStatus(201);
        $response->shouldNotBe($this->getWrappedObject());
        $response->shouldHaveType(Response::class);
        $response->getReasonPhrase()->shouldBe('Created');
    }
}
