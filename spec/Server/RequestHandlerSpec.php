<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Server;

use Interop\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Message\Response;
use Slick\Http\Message\Server\Request;
use PhpSpec\ObjectBehavior;
use Slick\Http\Server\RequestHandler;

/**
 * RequestHandlerSpec specs
 *
 * @package spec\Slick\Http\Server
 */
class RequestHandlerSpec extends ObjectBehavior
{
    /**
     * @var callable
     */
    private $callback;

    function let()
    {
        $this->callback = function (ServerRequestInterface $request) { return new Response(204); };
        $this->beConstructedWith($this->callback);
    }

    function its_a_request_handler()
    {
        $this->shouldBeAnInstanceOf(RequestHandlerInterface::class);
    }

    function it_is_initializable_with_a_callable()
    {
        $this->shouldHaveType(RequestHandler::class);
    }

    function it_handles_a_server_request_returning_a_response()
    {
        $this->handle(new Request())->shouldBeAnInstanceOf(ResponseInterface::class);
    }

}