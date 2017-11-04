<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slick\Http\Message\Server\UploadedFile;
use PhpSpec\ObjectBehavior;

include 'upload-functions.php';

/**
 * UploadedFileSpec specs
 *
 * @package spec\Slick\Http\Message\Server
 */
class UploadedFileSpec extends ObjectBehavior
{

    public static $isUploadFile = true;
    public static $moveUploadFile = true;

    function let()
    {
        self::$isUploadFile = true;
        self::$moveUploadFile = true;
        $content = 'Hello world, form a test!';
        $file = tempnam("/tmp", "FOO");
        file_put_contents($file, $content);
        $array = [
            'name' => 'test.txt',
            'type' => 'plain/text',
            'size' => strlen($content),
            'tmp_name' => $file,
            'error' => UPLOAD_ERR_OK
        ];
        $this->beConstructedThrough('create', [$array]);
    }

    function its_an_http_uploaded_file()
    {
        $this->shouldBeAnInstanceOf(UploadedFileInterface::class);
    }

    function it_is_initializable_from_an_uploaded_php_file_array()
    {
        $this->shouldHaveType(UploadedFile::class);
    }

    function it_has_a_stream_with_file_contents()
    {
        $this->getStream()->shouldBeAnInstanceOf(StreamInterface::class);
    }

    function it_has_a_file_size()
    {
        $content = 'Hello world, form a test!';
        $this->getSize()->shouldBe(strlen($content));
    }

    function it_has_a_client_file_name()
    {
        $this->getClientFilename()->shouldBe('test.txt');
    }

    function it_has_the_file_media_type()
    {
        $this->getClientMediaType()->shouldBe('plain/text');
    }

    function it_can_move_an_uploaded_file_to_a_new_location()
    {
        $this->moveTo(__DIR__.'/test.txt');
        $this->shouldThrow(\RuntimeException::class)
            ->during('getStream');
    }

    function it_cannot_move_to_unknown_locations()
    {
        $this->shouldThrow(\InvalidArgumentException::class)
            ->during('moveTo', ['some/where']);
    }

    function it_throws_exception_for_unfinished_uploads()
    {
        self::$isUploadFile = false;
        $this->shouldThrow(\RuntimeException::class)
            ->during('moveTo', [__DIR__.'/test.txt']);
    }

    function it_throws_exception_on_move_failure()
    {
        self::$moveUploadFile = false;
        $this->shouldThrow(\RuntimeException::class)
            ->during('moveTo', [__DIR__.'/test.txt']);
    }

    function it_throws_exception_on_consecutive_calls()
    {
        $this->moveTo(__DIR__.'/test.txt');
        $this->shouldThrow(\RuntimeException::class)
            ->during('moveTo', [__DIR__.'/test.txt']);
    }
}