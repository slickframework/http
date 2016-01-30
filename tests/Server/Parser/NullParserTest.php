<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\Http\Server\Parser;

use PHPUnit_Framework_TestCase as TestCase;
use Psr\Http\Message\StreamInterface;
use Slick\Http\Server\Parser\NullParser;

/**
 * Class NullParserTest
 *
 * @package Slick\Tests\Http\Server\Parser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class NullParserTest extends TestCase
{

    /**
     * @var NullParser
     */
    protected $parser;

    /**
     * Sets the SUT null parser object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->parser = new NullParser();
        /** @var StreamInterface $stream */
        $stream = $this->getMock(StreamInterface::class);
        $this->parser->setContent($stream);
    }

    /**
     * Should return null
     * @test
     */
    public function parse()
    {
        $this->assertNull($this->parser->parse());
    }
}
