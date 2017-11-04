<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server\BodyParser;

use Slick\Http\Message\Server\BodyParser\XmlParser;
use PhpSpec\ObjectBehavior;
use Slick\Http\Message\Server\BodyParserInterface;
use Slick\Http\Message\Stream\TextStream;

/**
 * XmlParserSpec specs
 *
 * @package spec\Slick\Http\Message\Server\BodyParser
 */
class XmlParserSpec extends ObjectBehavior
{

    function let()
    {
        $xml = <<<EOX
<?xml version="1.0" encoding="UTF-8"?>
<note>
  <to>Tove</to>
  <from>Jani</from>
  <heading>Reminder</heading>
  <body>Don't forget me this weekend!</body>
</note>
EOX;
        $stream = new TextStream($xml);
        $this->beConstructedWith($stream);
    }

    function its_a_body_parser()
    {
        $this->shouldBeAnInstanceOf(BodyParserInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(XmlParser::class);
    }

    function it_parses_the_body_as_xml()
    {
        $this->parse()->shouldBeAnInstanceOf(\SimpleXMLElement::class);
    }
}