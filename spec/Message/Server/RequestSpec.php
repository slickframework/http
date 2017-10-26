<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Message\Server;

use Slick\Http\Message\Server\Request;
use PhpSpec\ObjectBehavior;

/**
 * RequestSpec specs
 *
 * @package spec\Slick\Http\Message\Server
 */
class RequestSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Request::class);
    }
}