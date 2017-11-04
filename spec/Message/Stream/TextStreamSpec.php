<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Stream;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Exception\RuntimeException;
use Slick\Http\Message\Stream\TextStream;
use PhpSpec\ObjectBehavior;

/**
 * TextStreamSpec specs
 *
 * @package spec\Slick\Http\Message\Stream
 */
class TextStreamSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('hello world!');
    }


    function it_is_initializable()
    {
        $this->shouldHaveType(TextStream::class);
    }

    function its_a_psr7_stream()
    {
        $this->shouldHaveType(StreamInterface::class);
    }

    function its_writable()
    {
        $this->isWritable()->shouldBe(true);
        $this->write(' from test!');
        $this->rewind();
        $this->getContents()->shouldBe('hello world! from test!');
    }

    function it_can_be_used_as_string()
    {
        $this->__toString()->shouldBe('hello world!');
    }


    function it_can_detach_its_internal_stream_resource()
    {
        $this->detach()->shouldBeResource();
        $this->detach()->shouldBeNull();
    }

    function it_as_a_content_size()
    {
        $size = strlen('hello world!');
        $this->getSize()->shouldBe($size);
    }

    function it_can_tell_the_pointer_position()
    {
        $resource = fopen('php://memory', 'rw+');
        fputs($resource,'hello world!');
        $result = ftell($resource);
        fclose($resource);

        $this->tell()->shouldBe($result);
    }

    function it_can_tell_if_its_at_the_end_of_the__stream()
    {
        $this->eof()->shouldBe(false);
    }

    function it_can_check_if_stream_is_seekable()
    {
        $this->isSeekable()->shouldBe(true);
    }

    function it_can_seek_a_position_in_the_stream()
    {
        $this->seek(10)->shouldBe(true);
    }

    function it_can_be_rewind()
    {
        $this->rewind()->shouldBe(true);
    }

    function it_can_be_writable()
    {
        $this->isWritable()->shouldBe(true);
    }

    function it_is_readable()
    {
        $this->isReadable()->shouldBe(true);
        $this->rewind();
        $this->read(5)->shouldBe('hello');
    }

    function it_retrieves_all_remaining_content_in_the_stream()
    {
        $this->rewind();
        $this->getContents()->shouldBe('hello world!');
    }

    function it_has_metadata_values()
    {
        $resource = fopen('php://memory', 'rw+');
        fputs($resource, 'hello world!');
        $result = stream_get_meta_data($resource);
        fclose($resource);

        $this->getMetadata()->shouldBe($result);
        $this->getMetadata('mode')->shouldBe($result['mode']);
    }
}