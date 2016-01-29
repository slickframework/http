<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\UriInterface;

/**
 * Request message
 *
 * @package Slick\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Request extends Message implements RequestInterface
{

    /**
     * @var string
     */
    protected $method = '';

    /**
     * The request-target, if it has been provided or calculated.
     *
     * @var null|string
     */
    protected $requestTarget;

    /**
     * @var null|UriInterface
     */
    protected $uri;

    /**#@+
     * @var string HTTP request methods
     */
    const METHOD_OPTIONS  = 'OPTIONS';
    const METHOD_GET      = 'GET';
    const METHOD_HEAD     = 'HEAD';
    const METHOD_POST     = 'POST';
    const METHOD_PUT      = 'PUT';
    const METHOD_DELETE   = 'DELETE';
    const METHOD_TRACE    = 'TRACE';
    const METHOD_CONNECT  = 'CONNECT';
    const METHOD_PATCH    = 'PATCH';
    const METHOD_PROPFIND = 'PROPFIND';
    /**#@- */

    /**
     * Creates a request message.
     *
     * @param string $method
     * @param null   $target
     * @param array  $headers
     * @param string $body
     */
    public function __construct(
        $method = self::METHOD_GET, $target = null, array $headers = [],
        $body = 'php://memory'
    ) {
        parent::__construct($body, $headers);
        $this->method = $method;
        $this->requestTarget = $target;
        $target = (null === $target) ? '' : $target;
        $this->uri = new Uri($target);
    }

    /**
     * Retrieves the message's request target.
     *
     * Retrieves the message's request-target either as it will appear (for
     * clients), as it appeared at request (for servers), or as it was
     * specified for the instance (see withRequestTarget()).
     *
     * In most cases, this will be the origin-form of the composed URI,
     * unless a value was provided to the concrete implementation (see
     * withRequestTarget() below).
     *
     * If no URI is available, and no request-target has been specifically
     * provided, this method MUST return the string "/".
     *
     * @return string
     */
    public function getRequestTarget()
    {
        $target = ($this->uri) ? $this->targetFromUri() : '/';
        if (null !== $this->requestTarget) {
            $target = $this->requestTarget;
        }
        return $target;
    }

    /**
     * Return an instance with the specific request-target.
     *
     * If the request needs a non-origin-form request-target — e.g., for
     * specifying an absolute-form, authority-form, or asterisk-form —
     * this method may be used to create an instance with the specified
     * request-target, verbatim.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request target.
     *
     * @link http://tools.ietf.org/html/rfc7230#section-2.7 (for the various
     *     request-target forms allowed in request messages)
     * @param mixed $requestTarget
     * @return self
     */
    public function withRequestTarget($requestTarget)
    {
        $message = clone $this;
        $message->requestTarget = $requestTarget;
        return $message;
    }

    /**
     * Retrieves the HTTP method of the request.
     *
     * @return string Returns the request method.
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Return an instance with the provided HTTP method.
     *
     * While HTTP method names are typically all uppercase characters, HTTP
     * method names are case-sensitive and thus implementations SHOULD NOT
     * modify the given string.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * changed request method.
     *
     * @param string $method Case-sensitive method.
     * @return self
     * @throws \InvalidArgumentException for invalid HTTP methods.
     */
    public function withMethod($method)
    {
        $message = clone $this;
        $message->method = $method;
        return $message;
    }

    /**
     * Retrieves the URI instance.
     *
     * This method MUST return a UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @return UriInterface Returns a UriInterface instance
     *     representing the URI of the request.
     */
    public function getUri()
    {
        return $this->uri;
    }

    /**
     * Returns an instance with the provided URI.
     *
     * This method MUST update the Host header of the returned request by
     * default if the URI contains a host component. If the URI does not
     * contain a host component, any pre-existing Host header MUST be carried
     * over to the returned request.
     *
     * You can opt-in to preserving the original state of the Host header by
     * setting `$preserveHost` to `true`. When `$preserveHost` is set to
     * `true`, this method interacts with the Host header in the following ways:
     *
     * - If the the Host header is missing or empty, and the new URI contains
     *   a host component, this method MUST update the Host header in the returned
     *   request.
     * - If the Host header is missing or empty, and the new URI does not contain a
     *   host component, this method MUST NOT update the Host header in the returned
     *   request.
     * - If a Host header is present and non-empty, this method MUST NOT update
     *   the Host header in the returned request.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return an instance that has the
     * new UriInterface instance.
     *
     * @link http://tools.ietf.org/html/rfc3986#section-4.3
     * @param UriInterface $uri New request URI to use.
     * @param bool $preserveHost Preserve the original state of the Host header.
     * @return self
     */
    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        $message = clone $this;
        $message->uri = $uri;

        if ($preserveHost && $this->hasHeader('Host')) {
            return $message;
        }

        if ($uri) {
            $this->hostHeaderFromUri($message);
        }

        return $message;
    }

    /**
     * Gets the request target from current URI
     *
     * @return string
     */
    private function targetFromUri()
    {
        $target = $this->uri->getPath();
        $target .= ('' === $this->uri->getQuery())
            ? ''
            : '?'.$this->uri->getQuery();

        $target = empty($target) ? '/' : $target;
        return $target;
    }

    /**
     * Updates the host header from with the one defined on URI
     *
     * @param Request $request
     */
    private function hostHeaderFromUri(Request $request)
    {
        $host = $request->uri->getHost();
        if (! $host) {
            return;
        }

        $host = $request->uri->getPort()
            ? $host.':'.$request->uri->getPort()
            : $host;

        $request->setHeader('Host', $host);
    }
}