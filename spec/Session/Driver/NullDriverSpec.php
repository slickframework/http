<?php

declare(strict_types=1);

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
    public function its_a_session_driver()
    {
        $this->shouldBeAnInstanceOf(SessionDriverInterface::class);
    }

    public function it_is_initializable()
    {
        $this->shouldHaveType(NullDriver::class);
    }

    public function it_does_not_store_anything()
    {
        $this->set('foo', 'bar')->shouldBe($this->getWrappedObject());
        $this->get('foo')->shouldBe(null);
    }

    public function it_returns_the_default_when_retrieving_a_value()
    {
        $this->get('foo', false)->shouldBe(false);
    }

    public function it_does_not_erase_values()
    {
        $this->erase('foo')->shouldBe($this->getWrappedObject());
    }
}
