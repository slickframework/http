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

/**
 * HTTP Server Middleware decorator Interface
 *
 * @package Slick\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface MiddlewareInterface
{

    /**
     * Handles a Request and updated the response
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return ResponseInterface
     */
    public function handle(
        ServerRequestInterface $request, ResponseInterface $response
    );

    /**
     * The next middleware executed in the request handle process
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self|$this|MiddlewareInterface
     */
    public function setNext(MiddlewareInterface $middleware = null);
}