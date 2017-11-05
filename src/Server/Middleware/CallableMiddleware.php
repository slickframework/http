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
use Slick\Http\Message\Response;
use Slick\Http\Message\Stream\TextStream;
use Slick\Http\Server\Exception\UnexpectedValueException;

/**
 * Callable Middleware
 *
 * @package Slick\Http\Server\Middleware
*/
class CallableMiddleware implements MiddlewareInterface
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * Creates a callable Middleware
     *
     * @param callable $callable
     */
    public function __construct(callable $callable)
    {
        $this->callable = $callable;
    }

    /**
     * @param callable $callable
     * @param array    $arguments
     *
     * @return ResponseInterface
     */
    public static function execute(callable $callable, array $arguments)
    {
        $return = call_user_func_array($callable, $arguments);

        if ($return instanceof ResponseInterface) {
            return $return;
        }

        $canBeUsedAsText = is_null($return)
            || is_scalar($return)
            || (is_object($return) && method_exists($return, '__toString'));

        if (! $canBeUsedAsText) {
            throw new UnexpectedValueException(
                'The value returned must be scalar or an object with __toString method'
            );
        }

        return new Response(200, new TextStream((string) $return));
    }

    /**
     * Process an incoming server request and return a response, optionally delegating
     * response creation to an handler.
     *
     * @param ServerRequestInterface $request
     * @param RequestHandlerInterface $handler
     *
     * @return ResponseInterface
     */
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    )
    {
        return self::execute($this->callable, [$request, $handler]);
    }
}