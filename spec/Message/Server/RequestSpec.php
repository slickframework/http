<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server;

use Slick\Http\Message\Exception\InvalidArgumentException;
use Slick\Http\Message\Server\Request;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Server\UploadedFile;
use Slick\Http\Message\Stream\TextStream;

/**
 * RequestSpec specs
 *
 * @package spec\Slick\Http\Message\Server
 */
class RequestSpec extends ObjectBehavior
{

    public function __construct()
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
    }

    function its_an_http_request_message_with_client_incoming_data()
    {
        $this->shouldBeAnInstanceOf(\Slick\Http\Message\Request::class);
        $this->getMethod()->shouldBe('GET');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }

    function it_holds_an_environment_server_values()
    {
        $this->getServerParams()->shouldBe($_SERVER);
    }

    function it_can_hold_a_list_of_cookies()
    {
        $this->getCookieParams()->shouldBe($_COOKIE);
    }

    function it_can_create_an_instance_with_other_cookies()
    {
        $request = $this->withCookieParams(['foo' => 'bar']);
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldBeAnInstanceOf(Request::class);
        $request->getCookieParams()->shouldBe(['foo' => 'bar']);
    }

    function it_cah_have_a_list_of_query_params()
    {
        $_GET['foo'] = 'bar';
        $request = $this->withRequestTarget('/test?bar=baz&test=target');
        $request->getQueryParams()->shouldBe([
            'foo' => 'bar',
            'bar' => 'baz',
            'test' => 'target'
        ]);
    }

    function it_can_create_an_instance_with_other_query_params()
    {
        $request = $this->withQueryParams(['bar' => 'foo']);
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldBeAnInstanceOf(Request::class);
        $request->getQueryParams()->shouldBe(['bar' => 'foo']);
    }

    function it_holds_a_list_of_uploaded_files()
    {
        $_FILES = include 'files.php';
        $files = $this->getUploadedFiles();
        $files['file1']->shouldBeAnInstanceOf(UploadedFile::class);
    }

    function it_can_create_an_instance_with_other_uploaded_files()
    {
        $request = $this->withUploadedFiles([]);
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldBeAnInstanceOf(Request::class);
        $request->getUploadedFiles()->shouldBe([]);
    }

    function it_throws_an_exception_with_invalid_uploaded_files_tree()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('withUploadedFiles', [['foo' => 'bar']]);
    }

    function it_parses_data_from_body()
    {
        $body = new TextStream('foo=bar&bar=bas');
        $request = $this
            ->withBody($body)
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $request->getParsedBody()->shouldHaveKeyWithValue('bar', 'bas');
    }

    function it_can_create_an_instance_with_other_parsed_body_data()
    {
        $request = $this->withParsedBody(['foo' => 'bar']);
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldBeAnInstanceOf(Request::class);
        $request->getParsedBody()->shouldBe(['foo' => 'bar']);
    }

    function it_throws_an_exception_for_unsupported_data_types_on_parsed_body()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('withParsedBody', ['Hello there!']);
    }

    function it_holds_a_list_of_named_attributes()
    {
        $this->getAttributes()->shouldBeArray();
    }

    function it_can_create_a_new_instance_with_a_specific_attribute()
    {
        $request = $this->withAttribute('foo', 'bar');
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldBeAnInstanceOf(Request::class);
        $request->getAttribute('foo')->shouldBe('bar');
    }

    function it_can_create_an_instance_without_an_attribute()
    {
        $request = $this->withAttribute('test', 'fail')
            ->withAttribute('foo', 'bar')
            ->withoutAttribute('test');
        $request->shouldNotBe($this->getWrappedObject());
        $request->shouldBeAnInstanceOf(Request::class);
        $request->getAttribute('test', false)->shouldBe(false);
    }
}