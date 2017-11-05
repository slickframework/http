<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Session\Driver;

use Slick\Http\Session\Driver\NullDriver;
use PhpSpec\ObjectBehavior;
use Slick\Http\Session\SessionDriverInterface;

/**
 * NullDriverSpec specs
 *
 * @package spec\Slick\Http\Session\Driver
 */
class NullDriverSpec extends ObjectBehavior
{
    function its_a_session_driver()
    {
        $this->shouldBeAnInstanceOf(SessionDriverInterface::class);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(NullDriver::class);
    }

    function it_does_not_store_anything()
    {
        $this->set('foo', 'bar')->shouldBe($this->getWrappedObject());
        $this->get('foo')->shouldBe(null);
    }

    function it_returns_the_default_when_retrieving_a_value()
    {
        $this->get('foo', false)->shouldBe(false);
    }

    function it_does_not_erase_values()
    {
        $this->erase('foo')->shouldBe($this->getWrappedObject());
    }
}