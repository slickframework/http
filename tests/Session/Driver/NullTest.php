<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Session\Driver;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Session\Driver\NullDriver;

/**
 * Null session driver test
 *
 * @package Slick\Tests\Http\Session\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class NullTest extends TestCase
{

    /**
     * @var \Slick\Http\Session\Driver\Null
     */
    protected $driver;

    /**
     * Sets the SUT null driver object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->driver = new NullDriver();
    }

    /**
     * Clear for next test
     */
    protected function tearDown()
    {
        $this->driver = null;
        parent::tearDown();
    }

    /**
     * Should always return the default value
     * @test
     */
    public function getValue()
    {
        $default = 'test';
        $this->assertSame($default, $this->driver->get('test', $default));
    }

    /**
     * Should do nothing and return a self instance
     * @test
     */
    public function setValue()
    {
        $this->assertSame($this->driver, $this->driver->set('test', 'test'));
    }

    /**
     * Should do nothing and return a self instance
     * @test
     */
    public function eraseValue()
    {
        $this->assertSame($this->driver, $this->driver->erase('test'));
    }
}
