<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Server;

use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server\Exception\UnexpectedValueException;
use Slick\Http\Server\Middleware\CallableMiddleware;
use Slick\Http\Server\MiddlewareStack;
use PhpSpec\ObjectBehavior;

/**
 * MiddlewareStackSpec specs
 *
 * @package spec\Slick\Http\Server
 */
class MiddlewareStackSpec extends ObjectBehavior
{
    private $middlewareStack;

    function let(MiddlewareInterface $mX2)
    {
        $mX1 = new CallableMiddleware(function($request, $handler) {return $handler->handle($request);});
        $this->middlewareStack = [$mX1, $mX2];
        $this->beConstructedWith($this->middlewareStack);
    }

    function it_is_initializable_with_a_list_of_middleware_objects()
    {
        $this->shouldHaveType(MiddlewareStack::class);
    }

    function it_calls_every_middleware_in_the_stack(
        MiddlewareInterface $mX2,
        ServerRequestInterface $request,
        ResponseInterface $response
    )
    {
        $mX2->process($request, Argument::type(RequestHandlerInterface::class))
            ->shouldBeCalled()
            ->willReturn($response);
        $this->process($request)->shouldBe($response);
    }

    function it_throws_an_exception_if_returned_middleware_value_is_not_a_response(
        MiddlewareInterface $mX2,
        ServerRequestInterface $request
    )
    {
        $mX2->process($request, Argument::type(RequestHandlerInterface::class))
            ->shouldBeCalled()
            ->willThrow(new UnexpectedValueException('Error'));
        $this->beConstructedWith([$mX2]);
        $this->shouldThrow(UnexpectedValueException::class)
            ->during('process', [$request]);
    }
}
