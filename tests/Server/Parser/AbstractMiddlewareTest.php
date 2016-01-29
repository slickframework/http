<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server\Parser;

use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server\AbstractMiddleware;
use Slick\Http\Server\MiddlewareInterface;

/**
 * AbstractMiddleware Test case
 *
 * @package Slick\Tests\Http\Server\Parser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractMiddlewareTest extends TestCase
{

    /**
     * @var AbstractMiddleware
     */
    protected $middleWare;

    /**
     * Creates te SUT object instance
     */
    protected function setUp()
    {
        parent::setUp();
        $this->middleWare = $this->getMockForAbstractClass(
            AbstractMiddleware::class
        );
    }

    /**
     * Should check if there is a middle ware to run and runs it
     * @test
     */
    public function runNextHandler()
    {
        /** @var ServerRequestInterface $request */
        $request = $this->getMock(ServerRequestInterface::class);
        /** @var ResponseInterface $response */
        $response = $this->getMock(ResponseInterface::class);
        $next = $this->getMockBuilder(MiddlewareInterface::class)
            ->setMethods(['handle', 'setNext'])
            ->getMock();
        $next->expects($this->once())
            ->method('handle')
            ->with($request, $response)
            ->willReturn($response);
        $this->middleWare->setNext($next);
        $this->assertSame(
            $response,
            $this->middleWare->executeNext($request, $response)
        );
    }
}
