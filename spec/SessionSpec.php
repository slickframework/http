<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http;

use Slick\Http\Session;
use Slick\Http\Session\Exception\ClassNotFoundException;
use Slick\Http\Session\Exception\InvalidDriverClassException;
use PhpSpec\ObjectBehavior;

/**
 * SessionSpec specs
 *
 * @package spec\Slick\Http
 */
class SessionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(Session::DRIVER_NULL, []);
    }

    function it_is_initializable_with_a_driver_class_and_array_of_options()
    {
        $this->shouldHaveType(Session::class);
    }

    function it_initializes_a_session_driver()
    {
        $this->initialize()->shouldBeAnInstanceOf(Session::DRIVER_NULL);
    }

    function it_checks_that_the_driver_class_exists()
    {
        $this->beConstructedWith('Some\Unknown\ClassName');
        $this->shouldThrow(ClassNotFoundException::class)
            ->during('initialize');
    }

    function it_only_accepts_classes_that_implement_session_driver_interface()
    {
        $this->beConstructedWith('stdClass');
        $this->shouldThrow(InvalidDriverClassException::class)
            ->during('initialize');
    }
}