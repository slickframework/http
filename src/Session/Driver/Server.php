<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Session\Driver;

use Slick\Http\SessionDriverInterface;

/**
 * Server session driver
 *
 * @package Slick\Http\Session\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Server extends AbstractDriver implements SessionDriverInterface
{

    /**
     * Driver initialization callback
     *
     * @return mixed
     */
    protected function initialize()
    {
        session_set_cookie_params($this->lifetime, '/', $this->domain);
        session_name($this->name);
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
}