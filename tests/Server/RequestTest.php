<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server;

use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\UploadedFileInterface;
use Slick\Http\Server\Request;
use Slick\Http\Stream;

/**
 * Server Request test case
 *
 * @package Slick\Tests\Http\Server
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
        $stream = new Stream('php://memory', 'rw+');
        $stream->write(json_encode((object) ['foo' => 'bar']));
        $this->request = new Request(
            'GET',
            '/',
            ['content-type' => 'application/json'],
            $stream
        );
    }

    /**
     * Clears for next test
     */
    protected function tearDown()
    {
        $this->request = null;
        parent::tearDown();
    }

    /**
     * Should return an empty array
     * @test
     */
    public function serverParams()
    {
        $this->assertEquals([], $this->request->getServerParams());
    }

    /**
     * Should maintain immutability and return a new instance with
     * provided data.
     * @test
     */
    public function cookieParams()
    {
        $cookies = ['fooCookie', 'bar'];
        $request = $this->request->withCookieParams($cookies);
        $this->assertImmutable($request);
        $this->assertEquals($cookies, $request->getCookieParams());
    }

    /**
     * Should maintain immutability and return a new instance with
     * provided data.
     * @test
     */
    public function queryParams()
    {
        $query = ['fooQuery', 'bar'];
        $request = $this->request->withQueryParams($query);
        $this->assertImmutable($request);
        $this->assertEquals($query, $request->getQueryParams());
    }

    /**
     * Should maintain immutability and return a new instance with
     * provided data.
     * @test
     */
    public function parsedBody()
    {
        $parsedBody = ['foo', 'bar'];
        $request = $this->request->withParsedBody($parsedBody);
        $this->assertImmutable($request);
        $this->assertEquals($parsedBody, $request->getParsedBody());
    }

    /**
     * Should maintain immutability and return a new instance with
     * provided attribute.
     * @test
     * @return Request
     */
    public function withAttribute()
    {
        $request = $this->request->withAttribute('foo', 'bar');
        $this->assertImmutable($request);
        return $request;
    }

    /**
     * @param Request $request
     * @test
     * @depends withAttribute
     * @return Request
     */
    public function checkAttribute(Request $request)
    {
        $this->assertEquals('bar', $request->getAttribute('foo'));
        return $request;
    }

    /**
     * @param Request $request
     * @return Request
     * @test
     * @depends checkAttribute
     */
    public function withoutAttribute(Request $request)
    {
        $request = $request->withAttribute('baz', 'bum')
            ->withoutAttribute('foo');
        $this->assertImmutable($request);
        return $request;
    }

    /**
     * @param Request $request
     * @test
     * @depends withoutAttribute
     */
    public function getAttributes(Request $request)
    {
        $this->assertEquals(['baz' => 'bum'], $request->getAttributes());
    }

    /**
     * Assert immutability of the request
     *
     * @param ServerRequestInterface $request
     */
    protected function assertImmutable($request)
    {
        $this->assertNotSame($this->request, $request);
        $this->assertInstanceOf(ServerRequestInterface::class, $request);
    }

    /**
     * Should maintain immutability and return a new instance with
     * provided attribute.
     * @test
     */
    public function withUploadedFiles()
    {
        $files = $this->getUploadedFiles();
        $request = $this->request->withUploadedFiles($files);
        $this->assertImmutable($request);
        $this->assertEquals($files, $request->getUploadedFiles());
    }

    /**
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function setInvalidFiles()
    {
        $files = ['test' => new \stdClass()];
        $this->request->withUploadedFiles($files);
    }

    /**
     * @return array
     */
    protected function getUploadedFiles()
    {
        return [
            'file' => $this->getMock(UploadedFileInterface::class),
            'others' => [
                'one' => $this->getMock(UploadedFileInterface::class)
            ]
        ];
    }
}
