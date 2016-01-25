<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;

use Psr\Http\Message\StreamInterface;
use Slick\Http\Message;

/**
 * Message test case
 *
 * @package Slick\Tests\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MessageTest extends TestCase
{

    /**
     * @var Message
     */
    protected $message;

    /**
     * Sets the SUT message object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->message = new Message('php://memory', ['Accept' => 'text/xml']);
    }

    /**
     * Clears for next test
     */
    protected function tearDown()
    {
        $this->message = null;
        parent::tearDown();
    }

    /**
     * Should be 1.1
     * @test
     */
    public function defaultVersion()
    {
        $this->assertEquals('1.1', $this->message->getProtocolVersion());
    }

    /**
     * Should create the body with Steam object
     * @test
     */
    public function createDefaultBody()
    {
        $this->assertInstanceOf(
            StreamInterface::class,
            $this->message->getBody()
        );
    }

    /**
     * Should accept only 1.0 or 1.1 values and return a self instance
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function setVersion()
    {
        $this->assertSame($this->message, $this->message->withProtocolVersion('1.0'));
        $this->assertEquals('1.0', $this->message->getProtocolVersion());
        $this->message->withProtocolVersion('2');
    }

    /**
     * Should save the header and return a self instance
     * @test
     */
    public function addHeader()
    {
        $this->assertSame(
            $this->message,
            $this->message->withHeader('Content-Type', 'text/html')
        );
    }

    /**
     * Should replace the existent header value and return a self instance
     * @test
     */
    public function replaceHeader()
    {
        $this->message->withHeader('Content-Type', 'text/html');
        $this->message->withHeader('content-type', 'text/xml');
        $this->assertEquals(
            'text/xml',
            $this->message->getHeaderLine('content-type')
        );
    }

    /**
     * Should append the value to the existing header
     * @test
     */
    public function appendHeader()
    {
        $this->message->withHeader('Content-Type', 'text/html');
        $this->message->withAddedHeader('content-type', 'text/xml');
        $this->assertEquals(
            ['text/html', 'text/xml'],
            $this->message->getHeader('content-type')
        );
    }

    /**
     * Should remove header from message
     * @test
     */
    public function removeHeader()
    {
        $this->message->withHeader('Content-Type', 'text/html');
        $this->message->withAddedHeader('content-type', 'text/xml');
        $this->assertSame($this->message, $this->message->withoutHeader('Content-type'));
        $this->assertEquals(['Accept' => ['text/xml']], $this->message->getHeaders());
    }
}
