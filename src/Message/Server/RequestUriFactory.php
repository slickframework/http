<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UriInterface;
use Slick\Http\Message\Uri;

/**
 * RequestUriFactory
 *
 * @package Slick\Http\Message\Server
*/
class RequestUriFactory
{
    /**
     * @var ServerRequestInterface
     */
    private ServerRequestInterface $request;

    /**
     * Creates an URI based on provided request data
     *
     * @param ServerRequestInterface $request
     *
     * @return UriInterface|Uri
     */
    public function createUriFrom(ServerRequestInterface $request): UriInterface|Uri
    {
        $this->request = $request;
        return new Uri($this->generateUrl());
    }

    /**
     * Creates an URI based on provided request data
     *
     * @param ServerRequestInterface $request
     *
     * @return UriInterface|Uri
     */
    public static function create(ServerRequestInterface $request): UriInterface|Uri
    {
        $factory = new RequestUriFactory();
        return $factory->createUriFrom($request);
    }

    /**
     * Generates an URL from current request data
     *
     * @return string
     */
    private function generateUrl(): string
    {
        $hostHeader = $this->request->getHeaderLine('host');
        $defaultHost = \strlen($hostHeader) > 0 ? $hostHeader : 'unknown-host';
        $host = $this->getServerParam('SERVER_NAME', $defaultHost);
        $scheme = $this->getServerParam('REQUEST_SCHEME', 'http');
        $uri = $this->getServerParam('REQUEST_URI', '/');
        $port = $this->getServerParam('SERVER_PORT', '80');

        $port = \in_array($port, ['80', '443']) ? '' : ":$port";

        return "$scheme://$host$port$uri";
    }

    /**
     * Retrieve an request server parameter stored with provided name
     *
     * If no match is found the default value is returned instead
     *
     * @param string $name    $_SERVER super-global key name
     * @param mixed   $default
     *
     * @return null|string
     */
    private function getServerParam(string $name, mixed $default = null): ?string
    {
        $data = $this->request->getServerParams();
        if (\array_key_exists($name, $data)) {
            $default = trim($data[$name]);
        }
        return $default;
    }
}
