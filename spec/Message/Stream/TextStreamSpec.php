<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Stream;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Message\Stream\TextStream;
use PhpSpec\ObjectBehavior;

/**
 * TextStreamSpec specs
 *
 * @package spec\Slick\Http\Message\Stream
 */
class TextStreamSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith('hello world!');
    }


    public function it_is_initializable()
    {
        $this->shouldHaveType(TextStream::class);
    }

    public function its_a_psr7_stream()
    {
        $this->shouldHaveType(StreamInterface::class);
    }

    public function its_writable()
    {
        $this->isWritable()->shouldBe(true);
        $this->write(' from test!');
        $this->rewind();
        $this->getContents()->shouldBe('hello world! from test!');
    }

    public function it_can_be_used_as_string()
    {
        $this->__toString()->shouldBe('hello world!');
    }


    public function it_can_detach_its_internal_stream_resource()
    {
        $this->detach()->shouldBeResource();
        $this->detach()->shouldBeNull();
    }

    public function it_as_a_content_size()
    {
        $size = \strlen('hello world!');
        $this->getSize()->shouldBe($size);
    }

    public function it_can_tell_the_pointer_position()
    {
        $resource = fopen('php://memory', 'rw+');
        fputs($resource, 'hello world!');
        $result = ftell($resource);
        fclose($resource);

        $this->tell()->shouldBe($result);
    }

    public function it_can_tell_if_its_at_the_end_of_the__stream()
    {
        $this->eof()->shouldBe(false);
    }

    public function it_can_check_if_stream_is_seekable()
    {
        $this->isSeekable()->shouldBe(true);
    }

    public function it_can_seek_a_position_in_the_stream()
    {

        $this->seek(10);
        $this->read(2)->shouldBe('d!');
    }

    public function it_can_be_rewind()
    {
        $this->rewind();
        $this->read(2)->shouldBe('he');
    }

    public function it_can_be_writable()
    {
        $this->isWritable()->shouldBe(true);
    }

    public function it_is_readable()
    {
        $this->isReadable()->shouldBe(true);
        $this->rewind();
        $this->read(5)->shouldBe('hello');
    }

    public function it_retrieves_all_remaining_content_in_the_stream()
    {
        $this->rewind();
        $this->getContents()->shouldBe('hello world!');
    }

    public function it_has_metadata_values()
    {
        $resource = fopen('php://memory', 'rw+');
        fputs($resource, 'hello world!');
        $result = stream_get_meta_data($resource);
        fclose($resource);

        $this->getMetadata()->shouldBe($result);
        $this->getMetadata('mode')->shouldBe($result['mode']);
    }
}
