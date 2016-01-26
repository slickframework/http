<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Uri\Filter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Filter\Exception\CannotFilterValueException;
use Slick\Http\Uri\Filter\Scheme;

/**
 * Scheme filter test case
 *
 * @package Slick\Tests\Http\Uri\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class SchemeTest extends TestCase
{

    /**
     * @var Scheme
     */
    protected $filter;

    /**
     * Sets the SUT filter
     */
    protected function setUp()
    {
        parent::setUp();
        $this->filter = new Scheme();
    }

    public function testFilter()
    {
        $this->assertEquals('', $this->filter->filter('://'));
        $this->assertEquals('https', $this->filter->filter('HTTPS://'));
    }

    public function testBadSchema()
    {
        $this->setExpectedException(CannotFilterValueException::class);
        $this->filter->filter('#');
    }
}
