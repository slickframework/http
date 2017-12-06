<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slick\Http\Message\Server\RequestUriFactory;
use PhpSpec\ObjectBehavior;

/**
 * RequestUriFactorySpec specs
 *
 * @package spec\Slick\Http\Message\Server
 */
class RequestUriFactorySpec extends ObjectBehavior
{

    function let(ServerRequestInterface $request)
    {
        $request->getServerParams()->willReturn(
            [
                'SERVER_NAME' => 'my-server.org',
                'HTTP_HOST' => 'server.org',
                'REQUEST_SCHEME' => 'https',
                'SERVER_PORT' => '8443',
                'REQUEST_URI' => '/some-dir/yourpage.php?q=bogus&n=10#tes',
            ]
        );
        $request->getHeaderLine('host')->willReturn('server.org');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(RequestUriFactory::class);
    }

    function it_creates_an_uri_with_data_from_a_server_request(ServerRequestInterface $request)
    {
        $uri = $this->createUriFrom($request);
        $uri->shouldBeAnInstanceOf(UriInterface::class);
        $uri->getPath()->shouldBe('/some-dir/yourpage.php');
        $uri->__toString()->shouldBe('https://my-server.org:8443/some-dir/yourpage.php?q=bogus&n=10#tes');
    }
}