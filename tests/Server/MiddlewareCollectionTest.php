<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server;

use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use Slick\Http\Server\MiddlewareCollection;
use Slick\Http\Server\MiddlewareInterface;

/**
 * Middleware Collection Test Case
 *
 * @package Slick\Tests\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class MiddlewareCollectionTest extends TestCase
{

    /**
     * @var MiddlewareCollection
     */
    protected $collection;

    /**
     * Sets the SUT object instance
     */
    protected function setUp()
    {
        parent::setUp();
        $this->collection = new MiddlewareCollection();
    }

    /**
     * Clear SUT for next test
     */
    protected function tearDown()
    {
        $this->collection = null;
        parent::tearDown();
    }

    /**
     * Should append the object and return a self instance
     * @test
     */
    public function appendMiddleware()
    {
        $obj1 = $this->getMiddlewareDouble();
        $obj2 = $this->getMiddlewareDouble();

        $this->collection[] = $obj1;
        $result = $this->collection->append($obj2);
        $this->assertSame($obj2, $result[1]);
    }

    /**
     * should raise an exception or fatal error
     * @test
     */
    public function appendOtherObjectType()
    {
        $obj = (object)[];
        try {
            $this->collection[] = $obj;
            $this->fail("Collection shouldn't accept other object types.");
        } catch (\Exception $exp) {
            $this->assertInstanceOf('Exception', $exp);
        }
    }

    /**
     * @return MockObject|MiddlewareInterface
     */
    protected function getMiddlewareDouble()
    {
        $class = MiddlewareInterface::class;
        $methods = get_class_methods($class);
        /** @var MiddlewareInterface|MockObject $middleware */
        $middleware = $this->getMockBuilder($class)
            ->setMethods($methods)
            ->getMock();
        return $middleware;
    }
}
