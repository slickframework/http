<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Client;

use PhpSpec\Exception\Example\FailureException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use React\Promise\Promise;
use Slick\Http\Client\CurlHttpClient;
use PhpSpec\ObjectBehavior;
use Slick\Http\Client\CurlState;
use Slick\Http\Client\HttpClientAuthentication;
use Slick\Http\HttpClientInterface;
use Slick\Http\Message\Stream\TextStream;
use Slick\Http\Message\Uri;

include(__DIR__.'/mocked-functions.php');

/**
 * CurlHttpClientSpec specs
 *
 * @package spec\Slick\Http\Client
 */
class CurlHttpClientSpec extends ObjectBehavior
{

    function let(RequestInterface $request)
    {
        $request->getMethod()->willReturn('PUT');
        $request->getHeaders()->willReturn([
            'Content-Type' => ['application/json', 'charset=utf-8']
        ]);
        $request->getBody()->willReturn(new TextStream('Hello test!'));
        $request->getRequestTarget()->willReturn('/some/path?foo=bar');
        $this->beConstructedWith(
            new Uri('https://example.com'),
            new HttpClientAuthentication('john', 'secret'),
            [
                CURLOPT_TIMEOUT => 15
            ]
        );
    }

    function its_an_http_client()
    {
        $this->shouldBeAnInstanceOf(HttpClientInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CurlHttpClient::class);
    }

    function it_returns_a_promise_after_sending_the_request(RequestInterface $request)
    {
        $failed = false;
        $promise = $this->send($request);
        $promise->shouldBeAnInstanceOf(Promise::class);
        $promise->then(
            function(ResponseInterface $response) use (&$failed) {
                if ($response->getStatusCode() != 200) {
                    $failed =
                        'Expected a response status of 200, but got '.$response->getStatusCode()
                    ;
                }
            },
            function ($error) use (&$failed) {
                $failed = "No error was expected, but got: $error";
            }
        );

        if ($failed) throw new FailureException($failed);
    }

    function it_uses_curl_to_send_http_requests(RequestInterface $request)
    {
        $this->send($request);
        if (! is_resource(CurlState::$resource)) {
            throw new FailureException("cURL was not initialized");
        }
    }

    function it_can_set_the_http_authentication(RequestInterface $request)
    {
        $this->send($request);

        if (
            ! isset(CurlState::$options[CURLOPT_USERPWD]) ||
            CurlState::$options[CURLOPT_USERPWD] != "john:secret"
        ) {
            throw new FailureException("Authentication was not set...");
        }
    }

    function it_set_the_url_for_curl(RequestInterface $request)
    {
        $this->send($request);
        if (
            ! isset(CurlState::$options[CURLOPT_URL]) ||
            CurlState::$options[CURLOPT_URL] != 'https://example.com/some/path?foo=bar'
        ) {
            throw new FailureException("URL from constructor was not set...");
        }
    }

    function it_can_infer_url_from_request_uri(RequestInterface $request)
    {
        $uri = new Uri('http://example.org/some/path?foo=bar');
        $request->getUri()->willReturn($uri);
        $this->beConstructedWith();
        $this->send($request);

        if (
            ! isset(CurlState::$options[CURLOPT_URL]) ||
            CurlState::$options[CURLOPT_URL] != (string) $uri
        ) {
            throw new FailureException("URL was not set...");
        }
    }

    function it_sets_the_request_method_from_request(RequestInterface $request)
    {
        $this->send($request);

        if (
            ! isset(CurlState::$options[CURLOPT_CUSTOMREQUEST]) ||
            CurlState::$options[CURLOPT_CUSTOMREQUEST] != 'PUT'
        ) {
            throw new FailureException("Request method was not set...");
        }
    }

    function it_sets_the_request_headers_from_request(RequestInterface $request)
    {
        $expected = ['Content-Type: application/json; charset=utf-8'];
        $this->send($request);

        if (
            ! isset(CurlState::$options[CURLOPT_HTTPHEADER]) ||
            CurlState::$options[CURLOPT_HTTPHEADER] != $expected
        ) {
            throw new FailureException("Request headers were not set...");
        }
    }

    function it_sets_the_request_body_from_request(RequestInterface $request)
    {
        $this->send($request);

        if (
            ! isset(CurlState::$options[CURLOPT_POSTFIELDS]) ||
            CurlState::$options[CURLOPT_POSTFIELDS] != 'Hello test!'
        ) {
            throw new FailureException("Request body was not set...");
        }
    }
}