<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Exception\InvalidArgumentException;
use Slick\Http\Response;

/**
 * Response test case
 *
 * @package Slick\Tests\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ResponseTest extends TestCase
{

    /**
     * @var Response
     */
    protected $response;

    /**
     * Sets the SUT response object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->response = new Response();
    }

    /**
     * Clear for next test
     */
    protected function tearDown()
    {
        unset ($this->response);
        parent::tearDown();
    }

    public function testDefaultResponseInstance()
    {
        $this->assertEquals(200, $this->response->getStatusCode());
        $this->assertEquals('OK', $this->response->getReasonPhrase());
    }

    public function testInvalidCode()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        $this->response->withStatus(600);
    }
}
