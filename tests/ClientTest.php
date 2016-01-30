<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Client;
use Slick\Http\Request;
use Slick\Http\Response;

/**
 * HTTP Client test case
 *
 * @package Slick\Tests\Http
 */
class ClientTest extends TestCase
{

    /**
     * @var Client
     */
    protected $client;

    protected function setUp()
    {
        parent::setUp();
        $this->client = new Client(['base_uri' => 'https://httpbin.org']);
    }

    public function testGetApiCall()
    {
        $request = new Request(Request::METHOD_GET, '/get');
        $response = $this->client->send($request);
        $this->assertInstanceOf(Response::class, $response);
        return $response;
    }

    /**
     * @param Response $response
     * @test
     * @depends testGetApiCall
     */
    public function validateBody(Response $response)
    {
        $data = $response->getBody()->getContents();
        $object = json_decode($data);
        $this->assertEquals('httpbin.org', $object->headers->Host);
    }
}
