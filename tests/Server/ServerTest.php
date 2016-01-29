<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server;

use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server;
use Slick\Http\Server\MiddlewareInterface;

/**
 * Server Middleware Runner Test Case
 *
 * @package Slick\Tests\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ServerTest extends TestCase
{

    /**
     * @var Server
     */
    protected $runner;

    /**
     * Creates the SUT object
     */
    protected function setUp()
    {
        parent::setUp();
        /** @var RequestInterface $request */
        $request = $this->getMock(ServerRequestInterface::class);
        $this->runner = new Server($request);
    }

    /**
     * Clear SUT for next test
     */
    protected function tearDown()
    {
        $this->runner = null;
        parent::tearDown();
    }

    /**
     * Should create an empty collection if not exists
     * @test
     */
    public function createEmptyCollectionOnGet()
    {
        $collection = $this->runner->getMiddlewareCollection();
        $this->assertInstanceOf(
            Server\MiddlewareCollection::class,
            $collection
        );
    }

    /**
     * Should call collection append
     * @test
     */
    public function appendMiddleware()
    {
        /** @var MiddlewareInterface $middleware */
        $middleware = $this->getMock(MiddlewareInterface::class);
        $collection = $this->getMiddlewareCollectionMock();
        $collection->expects($this->once())
            ->method('append')
            ->with($this->isInstanceOf(MiddlewareInterface::class))
            ->willReturnSelf();
        $this->runner->setMiddlewareCollection($collection);
        $this->runner->add($middleware);
    }

    /**
     * Should nest middleware objects and call the handle() method
     * @test
     */
    public function runMiddlewareStack()
    {
        $obj1 = new MyMiddleWare();
        /** @var MyMiddleWare|MockObject  $obj2 */
        $obj2 = $this->getMockBuilder(MyMiddleWare::class)
            ->setMethods(['handle', 'setNext'])
            ->getMock();
        $obj2->expects($this->once())
            ->method('handle')
            ->with(
                $this->isInstanceOf(RequestInterface::class),
                $this->isInstanceOf(ResponseInterface::class)
            );
        $obj2->expects($this->once())
            ->method('setNext')
            ->with(null);

        $collection = $this->getMiddlewareCollectionMock();
        $collection->method('asArray')
            ->willReturn([$obj1, $obj2]);
        $this->runner->setMiddlewareCollection($collection);
        $this->runner->run();
    }

    /**
     * Create and returns a MiddlewareCollectionInterface mock
     *
     * @return MockObject|Server\MiddlewareCollectionInterface
     */
    protected function getMiddlewareCollectionMock()
    {
        $class = Server\MiddlewareCollectionInterface::class;
        $methods = get_class_methods($class);
        /** @var Server\MiddlewareCollectionInterface|MockObject $collection */
        $collection = $this->getMockBuilder($class)
            ->setMethods($methods)
            ->getMock();
        return $collection;
    }
}

/**
 * MyMiddleWare: fake object
 *
 * @package Slick\Tests\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MyMiddleWare implements MiddlewareInterface
{

    /**
     * @var MiddlewareInterface
     */
    private $next;

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
    )
    {
        if (null !== $this->next) {
            $response = $this->next->handle($request, $response);
        }
        return $response;
    }

    /**
     * The next middleware executed in the request handle process
     *
     * @param null|MiddlewareInterface $middleware
     *
     * @return self|$this|MiddlewareInterface
     */
    public function setNext(MiddlewareInterface $middleware = null)
    {
        $this->next = $middleware;
        return $this;
    }
}
