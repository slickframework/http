<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Session\Driver;

use PhpSpec\Exception\Example\FailureException;
use Slick\Http\Session\Driver\ServerDriver;
use PhpSpec\ObjectBehavior;
use Slick\Http\Session\SessionDriverInterface;

include('mocked_functions.php');

/**
 * ServerDriverSpec specs
 *
 * @package spec\Slick\Http\Session\Driver
 */
class ServerDriverSpec extends ObjectBehavior
{

    public static $sessionParams = [];
    public static $sessionState = PHP_SESSION_NONE;
    public static $sessionInitialized = false;

    private $params = [
        'name' => 'session_test',
        'domain' => 'example.org',
        'lifetime' => 20,
        'path' => '/'
    ];

    public function let()
    {
        $_SESSION = [
            'test_foo' => 'bar'
        ];
        $this->beConstructedWith(array_merge($this->params, ['prefix' => 'test_']));
    }

    function its_a_session_driver()
    {
        $this->shouldBeAnInstanceOf(SessionDriverInterface::class);
    }

    function it_is_initializable_with_an_optional_array_of_properties()
    {
        $this->shouldHaveType(ServerDriver::class);
        $this->shouldHaveBeenSet();
    }

    function it_retrieves_session_values()
    {
        $this->get('foo')->shouldBe('bar');
    }

    function it_returns_a_default_value_if_not_exists()
    {
        $this->get('bar', false)->shouldBe(false);
    }

    function it_can_set_a_value()
    {
        $this->set('bar', 'baz')->shouldBe($this->getWrappedObject());
        $this->get('bar', false)->shouldBe('baz');
    }

    function it_can_erase_a_value()
    {
        $this->erase('foo')->shouldBe($this->getWrappedObject());
        $this->get('foo', false)->shouldBe(false);
    }

    public function getMatchers(): array
    {
        return [
            'haveBeenSet' => function () {

                if (self::$sessionParams != $this->params) {
                    throw new FailureException(
                        "Session properties were not set properly."
                    );
                }

                if (! self::$sessionInitialized) {
                    throw new FailureException(
                        "Session was not initialized."
                    );
                }
                return true;
            }
        ];
    }
}