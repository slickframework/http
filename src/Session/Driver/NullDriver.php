<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Session\Driver;

use Slick\Http\Session\SessionDriverInterface;

/**
 * NullDriver
 *
 * @package Slick\Http\Session\Driver
*/
class NullDriver implements SessionDriverInterface
{
    /**
     * Returns the value store with provided key or the default value.
     *
     * @param string $key The key used to store the value in session.
     * @param string $default The default value if no value was stored.
     *
     * @return mixed The stored value or the default value if key
     *  was not found.
     */
    public function get($key, $default = null)
    {
        return $default;
    }

    /**
     * Set/Stores a provided values with a given key.
     *
     * @param string $key The key used to store the value in session.
     * @param mixed $value The value to store under the provided key.
     *
     * @return self|$this|SessionDriverInterface Self instance for
     *   method call chains.
     */
    public function set($key, $value)
    {
        return $this;
    }

    /**
     * Erases the values stored with the given key.
     *
     * @param string $key The key used to store the value in session.
     *
     * @return self|$this|SessionDriverInterface Self instance for
     *   method call chains.
     */
    public function erase($key)
    {
        return $this;
    }
}