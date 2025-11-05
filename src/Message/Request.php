<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Slick\Http\Message\Exception\InvalidArgumentException;
use Slick\Http\Message\Stream\TextStream;

/**
 * Request
 *
 * @package Slick\Http\Message
*/
class Request extends Message implements RequestInterface
{
    /**
     * @var string
     */
    protected string $method;

    /**
     * @var null|string
     */
    protected ?string $target = null;

    /**
     * @var null|UriInterface
     */
    private ?UriInterface $uri = null;

    /**
     * Creates an HTTP Request message
     *
     * @param string $method
     * @param string|UriInterface|null $target
     * @param string $body
     * @param array<string, string> $headers
     */
    public function __construct(
        string $method,
        UriInterface|string|null $target = null,
        StreamInterface|string $body = '',
        array $headers = []
    ) {
        $body = \is_string($body) ? new TextStream($body) : $body;
        $body->rewind();
        parent::__construct($body->getContents());
        $this->method = $method;

        $this->target = $target instanceof UriInterface
            ? $this->setUri($target)
            : $target;

        foreach ($headers as $name => $header) {
            $this->headers[$name] = [$header];
        }
    }

    /**
     * Retrieves the message's request target.
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget(): string
    {
        if (! $this->target && ! $this->uri) {
            return '/';
        }

        return $this->target ?: $this->getTargetFromUri();
    }

    /**
     * Return an instance with the specific request-target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-5.3 (for the various
     *     request-target forms allowed in request messages)
     * @param mixed $requestTarget
     * @return static
     */
    public function withRequestTarget($requestTarget): RequestInterface
    {
        $message = clone $this;
        $message->target = $requestTarget;
        return $message;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod(): string
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * @param string $method Case-sensitive method.
     * @return static
     * @throws InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod(string $method): RequestInterface
    {
        $method = strtoupper($method);
        $knownMethods = [
            'HEAD', 'OPTIONS', 'GET', 'POST', 'PUT',
            'DELETE', 'CONNECT', 'TRACE', 'PATCH', 'PURGE'
        ];

        if (! \in_array($method, $knownMethods)) {
            throw new InvalidArgumentException(
                "Invalid or unknown method name."
            );
        }

        $message = clone $this;
        $message->method = $method;
        return $message;
    }

    /**
     * Retrieves the URI instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return static
     * @SuppressWarnings(PHPMD)
     */
    public function withUri(UriInterface $uri, bool $preserveHost = false): RequestInterface
    {
        $message = clone $this;
        $message->setUri($uri, $preserveHost);
        return $message;
    }

    /**
     * Sets the request URI
     *
     * @param UriInterface $uri
     * @param bool $preserveHost
     * @SuppressWarnings(PHPMD)
     */
    protected function setUri(UriInterface $uri, bool $preserveHost = false): string
    {
        if (! $preserveHost && $uri->getHost() !== '') {
            $key = $this->headerKey('Host');
            $this->headers[$key] = [$uri->getHost()];
        }

        $this->uri = $uri;
        return $this->getTargetFromUri();
    }

    /**
     * Get the target from the uri
     *
     * @return string
     */
    private function getTargetFromUri(): string
    {
        $target  = "/{$this->uri->getPath()}";
        $target .= $this->uri->getQuery() !== ''
            ? "?{$this->uri->getQuery()}"
            : '';
        $target .= $this->uri->getFragment() !== ''
            ? "#{$this->uri->getFragment()}"
            : '';
        return str_replace('//', '/', $target);
    }
}
