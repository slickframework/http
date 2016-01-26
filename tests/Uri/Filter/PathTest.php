<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Uri\Filter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Uri\Filter\Path;

/**
 * Path Filer Test case
 *
 * @package Slick\Tests\Http\Uri\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class PathTest extends TestCase
{

    /**
     * @var Path
     */
    protected $filter;

    /**
     * Sets the SUT filter
     */
    protected function setUp()
    {
        parent::setUp();
        $this->filter = new Path();
    }

    public function testFilter()
    {
        $this->assertEquals('', $this->filter->filter(''));
    }
}
