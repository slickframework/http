<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Session;
use Slick\Http\Session\Driver\Null;

/**
 * Session factory test case
 *
 * @package Slick\Tests\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SessionTest extends TestCase
{

    /**
     * Should create a Null session driver
     * @test
     */
    public function createSessionData()
    {
        $session = Session::create(Session::DRIVER_NULL);
        $this->assertInstanceOf(Null::class, $session);
    }

    /**
     * Should throw an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function createUnknownDriver()
    {
        Session::create('_unknown_', ['test' => 'false']);
    }

    /**
     * Should throw an exception
     * @test
     * @expectedException \Slick\Http\Exception\InvalidArgumentException
     */
    public function createInvalidClass()
    {
        Session::create('stdClass', ['test' => 'false']);
    }

    /**
     * @test
     * @runInSeparateProcess
     */
    public function initializeSession()
    {
        $session = new Session();
        $this->assertInstanceOf(
            Session\Driver\Server::class,
            $session->initialize()
        );
    }
}
