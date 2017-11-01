<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * RequestHandler
 *
 * @package Slick\Http\Server
*/
class RequestHandler implements RequestHandlerInterface
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * Creates a Request Handler
     *
     * @param callable $callback
     */
    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    /**
     * Handle the request and return a response.
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request)
    {
        $response = call_user_func($this->callback, $request);
        return $response;
    }
}