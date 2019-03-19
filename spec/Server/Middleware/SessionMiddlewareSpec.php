<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Server\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use PhpSpec\Exception\Example\FailureException;
use Prophecy\Argument;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slick\Http\Message\Server\Request;
use Slick\Http\Server\Middleware\SessionMiddleware;
use PhpSpec\ObjectBehavior;
use Slick\Http\Session\SessionDriverInterface;

/**
 * SessionMiddlewareSpec specs
 *
 * @package spec\Slick\Http\Server\Middleware
 */
class SessionMiddlewareSpec extends ObjectBehavior
{

    function let(SessionDriverInterface $sessionDriver)
    {
        $this->beConstructedWith($sessionDriver);
    }

    function its_a_middleware()
    {
        $this->shouldBeAnInstanceOf(MiddlewareInterface::class);
    }

    function it_is_initializable_with_a_session_driver()
    {
        $this->shouldHaveType(SessionMiddleware::class);
    }

    function it_add_a_session_driver_to_the_request(
        RequestHandlerInterface $handler,
        ResponseInterface $response
    )
    {
        $request = new Request();

        /** @var ServerRequestInterface $serverRequest */
        $serverRequest = Argument::that(function (ServerRequestInterface $subject) {
            $driver = $subject->getAttribute('sessionDriver');
            if (! $driver instanceof SessionDriverInterface) {
                throw new FailureException(
                    "Expected an attributes with a session driver, but got none..."
                );
            }

            return true;
        });

        $handler->handle($serverRequest)
            ->shouldBeCalled()
            ->willReturn($response);
        $this->process($request, $handler)->shouldBe($response);
    }

}
