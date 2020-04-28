<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Client;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Slick\Http\Client\Exception\NetworkException;
use Slick\Http\Client\Exception\RequestException;
use Slick\Http\Message\Response;
use Slick\Http\Message\Uri;

/**
 * CurlHttpClient
 *
 * @package Slick\Http\Client
*/
final class CurlHttpClient implements ClientInterface
{
    /**
     * @var null|Uri
     */
    private $url;

    /**
     * @var null|HttpClientAuthentication
     */
    private $auth;

    /**
     * @var array
     */
    private $options = [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HEADER => true
    ];

    /**
     * @var resource
     */
    private $handler;

    /**
     * Creates a CURL HTTP Client
     *
     * @param Uri|null                      $url
     * @param HttpClientAuthentication|null $auth
     * @param array                         $options
     */
    public function __construct(Uri $url = null, HttpClientAuthentication $auth = null, array $options = [])
    {
        $this->handler = curl_init();
        $this->url = $url;
        $this->auth = $auth;

        foreach ($options as $name => $option) {
            $this->options[$name] = $option;
        }
    }

    /**
     * @inheritDoc
     */
    public function sendRequest(RequestInterface $request): ResponseInterface
    {
        $this->prepare($request);
        $result = curl_exec($this->handler);
        $errno = curl_errno($this->handler);

        switch ($errno) {
            case CURLE_OK:
                // All OK, no actions needed.
                break;
            case CURLE_COULDNT_RESOLVE_PROXY:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_COULDNT_CONNECT:
            case CURLE_OPERATION_TIMEOUTED:
            case CURLE_SSL_CONNECT_ERROR:
                throw new NetworkException($request, curl_error($this->handler));
            default:
                throw new RequestException($request, curl_error($this->handler));
        }

        return $this->createResponse($result);
    }

    /**
     * Prepares the cURL handler options
     *
     * @param RequestInterface $request
     */
    private function prepare(RequestInterface $request)
    {
        $this->reset($this->handler);
        $this->setUrl($request);
        $this->options[CURLOPT_CUSTOMREQUEST] = $request->getMethod();
        $this->setHeaders($request);
        $this->options[CURLOPT_POSTFIELDS] = (string) $request->getBody();

        if ($this->auth instanceof HttpClientAuthentication) {
            $this->options[CURLOPT_USERPWD] = "{$this->auth->username()}:{$this->auth->password()}";
            $this->options[CURLOPT_HTTPAUTH] = $this->auth->type();
        }

        curl_setopt_array($this->handler, $this->options);
    }

    /**
     * Sets the URL for cURL to use
     *
     * @param RequestInterface $request
     */
    private function setUrl(RequestInterface $request)
    {
        $target = $request->getRequestTarget();
        $parts = parse_url($target);

        $uri = $this->url instanceof Uri
            ? $this->url
            : $request->getUri();

        $uri = $uri->withPath($parts['path']);
        $uri = array_key_exists('query', $parts)
            ? $uri->withQuery($parts['query'])
            : $uri;

        $this->options[CURLOPT_URL] = (string) $uri;
    }

    /**
     * Sets the headers from the request
     *
     * @param RequestInterface $request
     */
    private function setHeaders(RequestInterface $request)
    {
        $headers = [];
        foreach ($request->getHeaders() as $header => $values) {
            $headers[] = "{$header}: ".implode('; ', $values);
        }
        $this->options[CURLOPT_HTTPHEADER] = $headers;
    }

    /**
     * Resets the cURL handler
     *
     * @param resource $ch
     */
    private function reset(&$ch)
    {
        $ch = curl_init();
    }

    /**
     * Creates a response from cURL execution result
     *
     * @param string $result
     *
     * @return Response
     */
    private function createResponse($result)
    {
        $status = curl_getinfo($this->handler, CURLINFO_HTTP_CODE);
        list($header, $body) = $this->splitHeaderFromBody($result);
        return new Response($status, trim($body), $this->parseHeaders($header));
    }

    /**
     * Splits the cURL execution result into header and body
     *
     * @param $result
     *
     * @return array
     */
    private function splitHeaderFromBody($result)
    {
        $header_size = curl_getinfo($this->handler, CURLINFO_HEADER_SIZE);

        $header = substr($result, 0, $header_size);
        $body = substr($result, $header_size);

        return [trim($header), trim($body)];
    }

    /**
     * Parses the HTTP message headers from header part
     *
     * @param string $header
     *
     * @return array
     */
    private function parseHeaders($header)
    {
        $lines = explode("\n", $header);
        $headers = [];
        foreach ($lines as $line) {
            if (strpos($line, ':') === false) {
                continue;
            }

            $middle=explode(":", $line);
            $headers[trim($middle[0])] = trim($middle[1]);
        }
        return $headers;
    }

    /**
     * Close the cURL handler on destruct
     */
    public function __destruct()
    {
        if (is_resource($this->handler)) {
            curl_close($this->handler);
        }
    }
}
