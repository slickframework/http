<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Slick\Http\Client;

use Slick\Http\Client\HttpClientAuthentication;
use PhpSpec\ObjectBehavior;

/**
 * AuthenticationSpec specs
 *
 * @package spec\Slick\Http\Client
 */
class HttpClientAuthenticationSpec extends ObjectBehavior
{

    function let()
    {
        $this->beConstructedWith('username', 'password', HttpClientAuthentication::AUTH_ANY);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(HttpClientAuthentication::class);
    }

    public function it_has_a_username()
    {
        $this->username()->shouldBe('username');
    }

    function it_has_a_password()
    {
        $this->password()->shouldBe('password');
    }

    function it_has_an_authentication_type()
    {
        $this->type()->shouldBe(HttpClientAuthentication::AUTH_ANY);
    }
}