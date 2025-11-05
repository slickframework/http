<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Request;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Uri;

/**
 * RequestSpec specs
 *
 * @package spec\Slick\Http\Message
 */
class RequestSpec extends ObjectBehavior
{
    private $uri;
    private $headers = [
        'Content-Type' => 'text/plain'
    ];

    public function let(StreamInterface $body)
    {
        $this->uri = new Uri('http://example.com/path?foo=bar');
        $body->__toString()->willReturn('Hello world!');
        $body->getContents()->willReturn('Hello world!');
        $body->rewind()->shouldBeCalled();
        $this->beConstructedWith('POST', $this->uri, $body, $this->headers);
    }

    public function its_an_http_request_message()
    {
        $this->shouldHaveType(RequestInterface::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    public function it_has_a_target()
    {
        $this->getRequestTarget()->shouldBe('/path?foo=bar');
    }

    public function it_can_create_a_message_with_new_target()
    {
        $request = $this->withRequestTarget('/path');
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldHaveType(Request::class);
        $request->getRequestTarget()->shouldBe('/path');
    }

    public function it_has_a_method()
    {
        $this->getMethod()->shouldBe('POST');
    }

    public function it_can_create_a_request_with_a_new_method()
    {
        $request = $this->withMethod('PUT');
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldHaveType(Request::class);
        $request->getMethod()->shouldBe('PUT');
    }

    public function it_has_an_uri()
    {
        $this->getUri()->shouldBe($this->uri);
        $this->getHeaderLine('host')->shouldBe('example.com');
    }

    public function it_can_create_a_request_with_new_uri()
    {
        $uri = new Uri('https://example.org');

        $request = $this->withUri($uri);
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldHaveType(Request::class);

        $request->getUri()->shouldBe($uri);
    }
}
