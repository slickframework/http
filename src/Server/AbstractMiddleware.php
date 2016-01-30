<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\PhpEnvironment\Response;

/**
 * AbstractMiddleware is a base class to create HTTP middleware objects
 *
 * @package Slick\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractMiddleware
{

    /**
     * @var MiddlewareInterface
     */
    protected $next;

    /**
     * The next middleware executed in the request handle process
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self|$this|MiddlewareInterface
     */
    public function setNext(MiddlewareInterface $middleware = null)
    {
        $this->next = $middleware;
        return $this;
    }

    /**
     * Executes the next middleware if it exists
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface|Response $response
     *
     * @return ResponseInterface|Response
     */
    public function executeNext(
        ServerRequestInterface $request, ResponseInterface $response
    ) {
        if (null !== $this->next) {
            $response = $this->next->handle($request, $response);
        }
        return $response;
    }
}
