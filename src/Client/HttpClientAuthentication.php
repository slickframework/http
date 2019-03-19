<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Client;

/**
 * Authentication
 *
 * @package Slick\Http\Client
*/
class HttpClientAuthentication
{
    const AUTH_BASIC  = CURLAUTH_BASIC;
    const AUTH_DIGEST = CURLAUTH_DIGEST;
    const AUTH_NTLM   = CURLAUTH_NTLM;
    const AUTH_ANY    = CURLAUTH_ANY;

    /**
     * @var string
     */
    private $username;

    /**
     * @var string
     */
    private $password;

    /**
     * @var int
     */
    private $type;

    /**
     * Creates an HTTP Client Authentication
     *
     * @param string $username
     * @param string $password
     * @param int    $type
     */
    public function __construct($username, $password, $type = self::AUTH_ANY)
    {
        $this->username = $username;
        $this->password = $password;
        $this->type = $type;
    }

    /**
     * User's name
     *
     * @return string
     */
    public function username()
    {
        return $this->username;
    }

    /**
     * User's password
     *
     * @return string
     */
    public function password()
    {
        return $this->password;
    }

    /**
     * Authentication type
     *
     * @return int
     */
    public function type()
    {
        return $this->type;
    }
}
