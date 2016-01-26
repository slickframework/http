<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\UriInterface;
use Slick\Http\Exception\InvalidArgumentException;
use Slick\Http\Uri;

/**
 * Uri test case
 *
 * @package Slick\Tests\Http
 * @author Filipe Silva <silvam.filipe@gmail.com>
 */
class UriTest extends TestCase
{

    /**
     * @var Uri
     */
    protected $uri;

    /**
     * Creates the SUT uri object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->uri = new Uri(
            'http://user:pass@example.com:8080/a/path?with=query#andFragment'
        );
    }

    /**
     * Clear before next text
     */
    protected function tearDown()
    {
        unset ($this->uri);
        parent::tearDown();
    }

    public function testStringRepresentation()
    {
        $uri = 'http://user:pass@example.com:8080/a/path?with=query#andFragment';
        $this->assertEquals($uri, (string) $this->uri);
        $this->assertEquals($uri, (string) $this->uri);
    }

    public function testNonStandardUri()
    {
        $str = 'https://localhost/some/path';
        $uri = new Uri($str);
        $this->assertEquals($str, (string) $uri);
    }

    public function testConstructInvalidUri()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new Uri(new \stdClass());
    }

    public function testAuthority()
    {
        $this->assertEquals('', $this->uri->withHost('')->getAuthority());
    }

    public function testHost()
    {
        $this->assertItDoesNotChange($this->uri, $this->uri->withHost('example.com'));
        $this->assertImmutable($this->uri, $this->uri->withHost('example.org'));
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withHost(new \stdClass());
    }

    public function testPath()
    {
        $uri = $this->uri->withPath('index.html');
        $this->assertImmutable($this->uri, $uri);

        $this->assertEquals(
            'http://user:pass@example.com:8080/index.html?with=query#andFragment',
            $uri->__toString()
        );
    }

    public function testInvalidPath()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withPath(new \stdClass());
    }

    public function testPathWithQueryString()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withPath('test?foo=bar');
    }

    public function testPathWithFragment()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withPath('test#foo');
    }

    public function testScheme()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->assertItDoesNotChange($this->uri, $this->uri->withScheme('http'));
        $this->assertImmutable($this->uri, $this->uri->withScheme('https'));
        $this->uri->withScheme(new \stdClass());
    }

    public function testFragment()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->assertItDoesNotChange($this->uri, $this->uri->withFragment('andFragment'));
        $this->assertImmutable($this->uri, $this->uri->withFragment('test'));
        $this->uri->withFragment(new \stdClass());
    }

    public function testUserInfo()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->assertItDoesNotChange($this->uri, $this->uri->withUserInfo('user', 'pass'));
        $this->assertImmutable($this->uri, $this->uri->withUserInfo('foo', 'bar'));
        $this->uri->withUserInfo(new \stdClass());

    }

    public function testInvalidPassword()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withUserInfo('foo', 123);
    }

    public function testQuery()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->assertItDoesNotChange($this->uri, $this->uri->withQuery('?with=query'));
        $this->assertImmutable($this->uri, $this->uri->withQuery('foo=bar'));
        $this->uri->withQuery(new \stdClass());
    }

    public function testQueryWithFragment()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withQuery('?test=2#end');
    }

    public function testPort()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->assertItDoesNotChange($this->uri, $this->uri->withPort(8080));
        $this->assertImmutable($this->uri, $this->uri->withPort(8088));
        $this->uri->withPort('Some value');
    }

    public function testInvalidPortNumber()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->uri->withPort(9584585);
    }

    protected function assertImmutable($source, $result)
    {
        $this->assertNotSame($source, $result);
        $this->assertInstanceOf(UriInterface::class, $result);
    }

    protected function assertItDoesNotChange($source, $result)
    {
        $this->assertSame($source, $result);
        $this->assertInstanceOf(UriInterface::class, $result);
    }
}
