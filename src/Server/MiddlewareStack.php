<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server\Exception\UnexpectedValueException;
use Slick\Http\Server\Middleware\CallableMiddleware;

/**
 * MiddlewareStack
 *
 * @package Slick\Http\Server
 */
class MiddlewareStack
{
    /**
     * @var list<MiddlewareInterface|callable>|MiddlewareInterface[]
     */
    private array $middlewareStack = [];

    /**
     * Creates a Middleware Stack
     *
     * @param list<MiddlewareInterface|callable>|MiddlewareInterface[]|callable[] $middlewareStack
     */
    public function __construct(array $middlewareStack)
    {
        foreach ($middlewareStack as $middleware) {
            $this->push($middleware);
        }
    }

    /**
     * Pushes a middleware to the stack
     *
     * @param MiddlewareInterface|callable $middleware
     *
     * @return MiddlewareStack
     */
    public function push(MiddlewareInterface|callable $middleware)
    {
        array_push($this->middlewareStack, $middleware);
        return $this;
    }

    /**
     * Processes all the middleware stack
     *
     * @param ServerRequestInterface $request
     *
     * @return ResponseInterface
     */
    public function process(ServerRequestInterface $request)
    {
        $handler = $this->resolve(0);
        return $handler->handle($request);
    }

    /**
     * Creates a request handler for middleware at the position defined by $index
     *
     * @param int $index
     *
     * @return RequestHandler
     *
     * @throws UnexpectedValueException If the return form a middleware is not a ResponseInterface
     */
    private function resolve($index)
    {
        return new RequestHandler(function (ServerRequestInterface $request) use ($index) {

            $middleware = isset($this->middlewareStack[$index])
                ? $this->middlewareStack[$index]
                : new CallableMiddleware(function () {
                });

            if ($middleware instanceof \Closure) {
                $middleware = new CallableMiddleware($middleware);
            }

            $nextHandler = $this->resolve($index + 1);
            $response = match (true) {
                $middleware instanceof MiddlewareInterface => $middleware->process($request, $nextHandler),
                default => $middleware($request, $nextHandler),
            };


            return $response;
        });
    }
}
