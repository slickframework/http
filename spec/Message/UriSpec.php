<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message;

use Psr\Http\Message\UriInterface;
use Slick\Http\Message\Exception\InvalidArgumentException;
use Slick\Http\Message\Uri;
use PhpSpec\ObjectBehavior;

/**
 * UriSpec specs
 *
 * @package spec\Slick\Http\Message
 */
class UriSpec extends ObjectBehavior
{
    private $url = 'https://user:pass@example.com:4443/the/path/file.html?test=1&foo=bar#example';

    function let()
    {
        $this->beConstructedWith($this->url);
    }

    function its_an_http_uri()
    {
        $this->shouldHaveType(UriInterface::class);
    }

    function it_is_initializable_with_an_url()
    {
        $this->shouldHaveType(Uri::class);
    }

    function it_only_accepts_valid_url_on_initialization()
    {
        $this->beConstructedWith('tel:+1-816-555-1212');
        $this->shouldThrow(InvalidArgumentException::class)
            ->duringInstantiation();
    }

    function it_has_a_scheme()
    {
        $this->getScheme()->shouldBe('https');
    }

    function it_can_create_an_uri_with_a_new_scheme()
    {
        $uri = $this->withScheme('http');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getScheme()->shouldBe('http');
    }

    function it_throws_an_exception_for_invalid_scheme_string()
    {
        $this->shouldThrow(InvalidArgumentException::class)
            ->during('withScheme', ['2http']);

    }

    function it_has_user_information()
    {
        $this->getUserInfo()->shouldBe('user:pass');
    }

    function it_can_create_an_uri_with_new_user_info()
    {
        $uri = $this->withUserInfo('me');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getUserInfo()->shouldBe('me');
    }

    function it_has_a_host()
    {
        $this->getHost()->shouldBe('example.com');
    }

    function it_can_create_an_uri_with_new_host()
    {
        $uri = $this->withHost('example.org');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getHost()->shouldBe('example.org');

        $this->shouldThrow(InvalidArgumentException::class)
            ->during('withHost', ['2 http']);
    }

    function it_has_an_authority()
    {
        $this->getAuthority()->shouldBe('user:pass@example.com:4443');
    }

    function it_has_a_port()
    {
        $this->getPort()->shouldBe(4443);
    }

    function it_can_create_an_uri_with_a_new_port()
    {
        $uri = $this->withPort('443');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getPort()->shouldBeNull();
        $uri->getAuthority()->shouldBe('user:pass@example.com');
    }

    function it_has_a_path()
    {
        $this->getPath()->shouldBe('/the/path/file.html');
    }

    function it_can_create_an_uri_with_a_new_path()
    {
        $uri = $this->withPath('/to/path/index');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getPath()->shouldBe('/to/path/index');
    }

    function it_has_a_fragment()
    {
        $this->getFragment()->shouldBe('example');
    }

    function it_can_create_an_uri_with_a_new_fragment()
    {
        $uri = $this->withFragment('test');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getFragment()->shouldBe('test');
    }

    function it_has_a_query()
    {
        $this->getQuery()->shouldBe('test=1&foo=bar');
    }

    function it_can_create_an_uri_with_a_new_query()
    {
        $uri = $this->withQuery('foo=bar&baz=test');
        $uri->shouldNotBe($this->getWrappedObject());
        $uri->shouldHaveType(Uri::class);
        $uri->getQuery()->shouldBe('foo=bar&baz=test');
    }

    function it_can_be_converted_to_string()
    {
        $this->__toString()->shouldBe($this->url);
    }
}