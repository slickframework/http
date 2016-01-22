<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Session\Driver;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Session\Driver\AbstractDriver;

/**
 * Abstract Driver test case
 *
 * @package Slick\Tests\Http\Session\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class AbstractDriverTest extends TestCase
{

    /**
     * @var AbstractDriver
     */
    protected $driver;

    /**
     * Creates the STU driver object and sets session dummy data
     */
    protected function setUp()
    {
        parent::setUp();
        $this->driver = $this->getMockForAbstractClass(AbstractDriver::class);
        // Some dummy session data
        $_SESSION = [
            'slick_foo' => 'bar'
        ];
    }

    /**
     * Clear all for next test
     */
    protected function tearDown()
    {
        $this->driver = null;
        parent::tearDown();
    }

    /**
     * Should return the value stored under the 'foo' key
     * @test
     */
    public function getValue()
    {
        $this->assertEquals('bar', $this->driver->get('foo'));
    }

    /**
     * Should change the session value an return a self instance
     * @test
     */
    public function setValue()
    {
        $this->assertSame($this->driver, $this->driver->set('foo', 'test'));
        $this->assertEquals('test', $_SESSION['slick_foo']);
    }

    /**
     * Should return the default value if no key is found in session global
     * @test
     */
    public function getDefaultValue()
    {
        $this->assertFalse($this->driver->get('test', false));
    }

    /**
     * Should delete the value and key from session global
     * @test
     */
    public function erase()
    {
        $this->assertSame($this->driver, $this->driver->erase('foo'));
        $this->assertFalse(array_key_exists('slick_foo', $_SESSION));
    }
}
