<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\PhpEnvironment;

use PHPUnit_Framework_TestCase as TestCase;

use Slick\Http\PhpEnvironment\Response;
use Slick\Http\Stream;

/**
 * PHP Environment Response test case
 *
 * @package Slick\Tests\Http\PhpEnvironment
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ResponseTest extends TestCase
{

    /**
     * @coverage Response::headersSent
     * @runInSeparateProcess
     *
     * @return Response
     */
    public function testHeadersWereSent()
    {
        $response = new Response();
        $response = $response->withHeader('content-type', 'application/json');
        $response->sendHeaders();
        $this->assertTrue($response->headersSent());
        return $response;
    }

    /**
     * @param Response $response
     * @depends testHeadersWereSent
     *
     * @return Response
     */
    public function testSendingHeadersAlwaysReturnsResponse($response)
    {
        $this->assertInstanceOf(
            "Slick\\Http\\PhpEnvironment\\Response",
            $response->sendHeaders()
        );
        return $response;
    }

    /**
     * @depends testSendingHeadersAlwaysReturnsResponse
     * @param Response $response
     */
    public function testSendOutputsContent($response)
    {
        $body = new Stream('php://memory', 'wr+');
        $body->write("Hello world!");
        $response = $response->withBody($body);
        $this->expectOutputString("Hello world!");
        $response->send();
        $this->assertAttributeEquals(true,'contentSent', $response);
    }

}
