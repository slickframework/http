<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Server\Middleware;

use Interop\Http\Server\MiddlewareInterface;
use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Message\Response;
use Slick\Http\Server\Exception\UnexpectedValueException;
use Slick\Http\Server\Middleware\CallableMiddleware;
use PhpSpec\ObjectBehavior;

/**
 * CallableMiddlewareSpec specs
 *
 * @package spec\Slick\Http\Server\Middleware
 */
class CallableMiddlewareSpec extends ObjectBehavior
{
    private $callable;

    function let()
    {
        $this->callable = function () {return new Response(200);};
        $this->beConstructedWith($this->callable);
    }

    function its_a_middleware()
    {
        $this->shouldBeAnInstanceOf(MiddlewareInterface::class);
    }

    function it_is_initializable_with_a_callable()
    {
        $this->shouldHaveType(CallableMiddleware::class);
    }

    function it_throws_an_exception_if_it_cannot_create_a_response_from_return(
        ServerRequestInterface $request,
        RequestHandlerInterface $requestHandler
    )
    {
        $this->beConstructedWith(function (){return (object)[];});
        $this->shouldThrow(UnexpectedValueException::class)
            ->during('process', [$request, $requestHandler]);
    }
}