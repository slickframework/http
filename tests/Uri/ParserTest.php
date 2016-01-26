<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Uri;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Exception\InvalidArgumentException;
use Slick\Http\Uri;
use Slick\Http\Uri\Parser;

/**
 * URI Parser test case
 *
 * @package Slick\Tests\Http\Uri
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ParserTest extends TestCase
{

    public function testEmptyUri()
    {
        $uri = new Uri();
        $result = Parser::parse('', $uri);
        $this->assertSame($uri, $result);
    }

    public function testMalformedParser()
    {
        $this->setExpectedException(InvalidArgumentException::class);
        new Uri(':8080/test:8080');
    }
}
