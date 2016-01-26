<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Uri\Filter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Uri\Filter\Query;

/**
 * Query filter test
 *
 * @package Slick\Tests\Http\Uri\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class QueryTest extends TestCase
{

    /**
     * @var Query
     */
    protected $filter;

    /**
     * Sets the SUT filter
     */
    protected function setUp()
    {
        parent::setUp();
        $this->filter = new Query();
    }

    public function testFilter()
    {
        $this->assertEquals('foo%5Bother%5D=bar&baz', $this->filter->filter('?foo[other]=bar&baz'));
    }
}
