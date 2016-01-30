<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Uri\Filter;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Uri\Filter\Fragment;

/**
 * Fragment Filter test case
 *
 * @package Slick\Tests\Http\Uri\FIlter
 * @
 */
class FragmentTest extends TestCase
{

    /**
     * @var Fragment
     */
    protected $filter;

    /**
     * Sets the SUT filter
     */
    protected function setUp()
    {
        parent::setUp();
        $this->filter = new Fragment();
    }

    public function testFilter()
    {
        $this->assertEquals('test', $this->filter->filter('#test'));
    }
}
