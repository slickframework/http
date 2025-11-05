<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Session\Driver;

use Slick\Http\Session\SessionDriverInterface;

/**
 * ServerDriver
 *
 * @package Slick\Http\Session\Driver
*/
class ServerDriver extends AbstractDriver implements SessionDriverInterface
{
    /**
     * Creates a Server Session Driver
     *
     * @param array<string, mixed> $options
     */
    public function __construct(array $options = [])
    {
        parent::__construct($options);

        session_set_cookie_params($this->lifetime, '/', $this->domain);
        session_name($this->name);
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }
    }
}
