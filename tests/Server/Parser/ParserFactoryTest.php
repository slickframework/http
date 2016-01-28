<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server\Parser;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\Http\Request;
use Slick\Http\Server\Parser\NullParser;
use Slick\Http\Server\Parser\ParserFactory;
use Slick\Http\Server\Parser\UrlEncodedPost;

/**
 * Parser Factory test case
 *
 * @package Slick\Tests\Http\Server\Parser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ParserFactoryTest extends TestCase
{

    /**
     * Should create a Url Encoded Post parser when no header is given
     * @test
     */
    public function createNullParser()
    {
        $request = new Request();
        $parser = ParserFactory::getParserFor($request);
        $this->assertInstanceOf(NullParser::class, $parser);
    }

    /**
     * Should create a null parser when no header is given
     * @test
     */
    public function createUrlEncodedParser()
    {
        /** @var Request $request */
        $request = (new Request())
            ->withHeader('Content-Type', 'application/x-www-form-urlencoded');
        $parser = ParserFactory::getParserFor($request);
        $this->assertInstanceOf(UrlEncodedPost::class, $parser);
    }
}
