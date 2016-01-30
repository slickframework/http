<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Psr\Http\Message\UriInterface;
use Slick\Http\Request;

/**
 * Request message test case
 *
 * @package Slick\Tests\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class RequestTest extends TestCase
{

    /**
     * @var Request
     */
    protected $request;

    /**
     * Creates the SUT request object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->request = new Request();
    }

    /**
     * Should return "/"
     * @test
     */
    public function emptyTargetRequest()
    {
        $this->assertEquals('/', $this->request->getRequestTarget());
    }

    /**
     * Should return the "/"
     * @test
     */
    public function targetFromEmptyPathUri()
    {
        $uri = $this->getUriMock();
        $uri->expects($this->once())
            ->method('getPath')
            ->willReturn('');
        $uri->expects($this->once())
            ->method('getQuery')
            ->willReturn('');
        $request = $this->request->withUri($uri);
        $this->assertEquals('/', $request->getRequestTarget());
    }

    /**
     * Should use the target from URI's path
     * @test
     */
    public function targetWithPathFromUri()
    {
        $uri = $this->getUriMock();
        $uri->expects($this->once())
            ->method('getPath')
            ->willReturn('/index.html');
        $uri->expects($this->once())
            ->method('getQuery')
            ->willReturn('');
        $request = $this->request->withUri($uri);
        $this->assertEquals('/index.html', $request->getRequestTarget());
    }

    /**
     * Should use the target and query from the URI
     * @test
     */
    public function targetWithPathAndQueryFromUri()
    {
        $uri = $this->getUriMock();
        $uri->expects($this->once())
            ->method('getPath')
            ->willReturn('/index.html');
        $uri->expects($this->atLeast(2))
            ->method('getQuery')
            ->willReturn('foo=bar&baz');
        $request = $this->request
            ->withHeader('host', 'localhost')
            ->withUri($uri, true);
        $this->assertEquals(
            '/index.html?foo=bar&baz',
            $request->getRequestTarget()
        );
    }

    /**
     * Should return the target that was explicitly set
     * @test
     */
    public function explicitTarget()
    {
        $request = $this->request->withRequestTarget('*');
        $this->assertEquals('*', $request->getRequestTarget());
    }

    /**
     * Should set the header "host" from the provided URI
     * @test
     */
    public function hostFromUri()
    {
        $uri = $this->getUriMock();
        $uri->expects($this->atLeast(2))
            ->method('getPort')
            ->willReturn('8080');
        $uri->expects($this->once())
            ->method('getHost')
            ->willReturn('example.com');
        $message = $this->request->withUri($uri);
        $this->assertEquals('example.com:8080', $message->getHeaderLine('host'));
        $this->assertSame($uri, $message->getUri());
    }

    public function testMethod()
    {
        $request = $this->request->withMethod(Request::METHOD_POST);
        $this->assertEquals(Request::METHOD_POST, $request->getMethod());
    }

    /**
     * Create an URI mocked from its interface
     *
     * @return MockObject|UriInterface
     */
    protected function getUriMock()
    {
        $class = UriInterface::class;
        $methods = get_class_methods($class);
        /** @var UriInterface|MockObject $uri */
        $uri = $this->getMockBuilder($class)
            ->setMethods($methods)
            ->getMock();
        return $uri;
    }
}
