<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Session\SessionDriverInterface;

/**
 * SessionMiddleware
 *
 * @package Slick\Http\Server\Middleware
*/
class SessionMiddleware implements MiddlewareInterface
{
    /**
     * @var SessionDriverInterface
     */
    private $sessionDriver;

    /**
     * SessionMiddleware constructor.
     * @param SessionDriverInterface $sessionDriver
     */
    public function __construct(SessionDriverInterface $sessionDriver)
    {
        $this->sessionDriver = $sessionDriver;
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to a handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $request = $request->withAttribute('sessionDriver', $this->sessionDriver);
        return $handler->handle($request);
    }
}