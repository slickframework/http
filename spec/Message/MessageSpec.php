<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message;

use Slick\Http\Message\Message;
use PhpSpec\ObjectBehavior;

/**
 * MessageSpec specs
 *
 * @package spec\Slick\Http\Message
 */
class MessageSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Message::class);
    }
}