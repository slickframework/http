<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 02-11-2017
 * Time: 17:47
 */

namespace Slick\Http\Session\Driver;

use Slick\Http\Session\SessionDriverInterface;

/**
 * Class AbstractDriver
 * @package Slick\Http\Session\Driver
 */
abstract class AbstractDriver implements SessionDriverInterface
{
    /**
     * @var null|string
     */
    protected $domain = null;

    /**
     * @var int
     */
    protected $lifetime = 0;

    /**
     * @var string
     */
    protected $name = 'ID';

    /**
     * @var string
     */
    protected $prefix = 'slick_';

    /**
     * Creates a Session Driver
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        foreach ($options as $option => $value) {
            if (property_exists(__CLASS__, $option)) {
                $this->$option = $value;
            }
        }
    }

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
        if (array_key_exists("{$this->prefix}{$key}", $_SESSION)) {
            $default = $_SESSION["{$this->prefix}{$key}"];
        }
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
        $_SESSION["{$this->prefix}{$key}"] = $value;
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
        unset($_SESSION["{$this->prefix}{$key}"]);
        return $this;
    }
}