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
use Slick\Http\Server\Parser\UrlEncodedPost;

/**
 * Url Encoded Post test case
 *
 * @package Slick\Tests\Http\Server\Parser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class UrlEncodedPostTest extends TestCase
{

    /**
     * @var UrlEncodedPost
     */
    protected $parser;

    /**
     * Sets the SUT null parser object
     */
    protected function setUp()
    {
        parent::setUp();
        $this->parser = new UrlEncodedPost();
        /** @var StreamInterface $stream */
        $stream = $this->getMock(StreamInterface::class);
        $this->parser->setContent($stream);
    }

    /**
     * Should return $_POST super global
     * @test
     */
    public function parse()
    {
        $_POST = ['foo' => 'bar'];
        $this->assertEquals($_POST, $this->parser->parse());
    }
}
