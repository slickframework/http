<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;
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
}
