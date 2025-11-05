<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message;

use Psr\Http\Message\MessageInterface;
use Slick\Http\Message\Message;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Stream\TextStream;

/**
 * MessageSpec specs
 *
 * @package spec\Slick\Http\Message
 */
class MessageSpec extends ObjectBehavior
{
    public function it_an_http_message()
    {
        $this->shouldHaveType(MessageInterface::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(Message::class);
    }

    public function it_has_a_protocol_version()
    {
        $this->getProtocolVersion()->shouldBe("1.1");
    }

    public function it_creates_message_with_new_protocol_version()
    {
        $message = $this->withProtocolVersion("1.0");
        $message->shouldNotBe($this->getWrappedObject());
        $message->shouldHaveType(Message::class);
        $message->getProtocolVersion()->shouldBe('1.0');
    }

    public function it_has_a_list_of_headers()
    {
        $this->getHeaders()->shouldBeArray();
    }

    public function it_creates_message_with_new_header()
    {
        $message = $this->withHeader('Content-Type', 'text/xml');
        $message->shouldNotBe($this->getWrappedObject());
        $message->shouldHaveType(Message::class);
        $message->hasHeader('content-type')->shouldBe(true);
        $message->getHeader('content-type')->shouldBe(['text/xml']);
    }

    public function it_can_retrieve_header_lines()
    {
        $message = $this->withHeader('Content-Type', 'text/xml');
        $message = $message->withAddedHeader('Content-type', ['utf-8']);
        $message->shouldNotBe($this->getWrappedObject());
        $message->shouldHaveType(Message::class);
        $message->getHeaderLine('content-Type')->shouldBe('text/xml,utf-8');
    }

    public function it_creates_message_without_a_header()
    {
        $source = $this->withHeader('Content-Type', 'text/xml');
        $message = $source->withoutHeader('content-type');
        $message->shouldNotBe($this->getWrappedObject());
        $message->shouldHaveType(Message::class);
        $message->hasHeader('content-type')->shouldBe(false);
        $source->hasHeader('content-type')->shouldBe(true);
    }

    public function it_has_a_body_content_stream()
    {
        $body = $this->getBody();
        $body->shouldHaveType(TextStream::class);
        $body->getSize()->shouldBe(0);
    }

    public function it_creates_a_message_with_a_new_body()
    {
        $body = new TextStream('body');
        $message = $this->withBody($body);
        $message->shouldNotBe($this->getWrappedObject());
        $message->shouldHaveType(Message::class);
        $message->getBody()->shouldBe($body);
    }
}
