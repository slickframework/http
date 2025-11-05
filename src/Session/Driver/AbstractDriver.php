<?php

declare(strict_types=1);

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
    protected ?string $domain = null;

    /**
     * @var int
     */
    protected int $lifetime = 0;

    /**
     * @var string
     */
    protected string $name = 'ID';

    /**
     * @var string
     */
    protected string $prefix = 'slick_';

    /**
     * Creates a Session Driver
     *
     * @param array<string, mixed> $options
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
    public function get($key, $default = null): mixed
    {
        $sessionKey = $this->prefix . $key;
        if (\array_key_exists($sessionKey, $this->fetchSession())) {
            $default = $this->fetchSession()[$sessionKey];
        }
        return $default;
    }

    /**
     * Set/Stores a provided values with a given key.
     *
     * @param string $key The key used to store the value in session.
     * @param mixed $value The value to store under the provided key.
     *
     * @return self Self instance for
     *   method call chains.
     * @SuppressWarnings(PHPMD)
     */
    public function set($key, $value): self
    {
        $sessionKey = $this->prefix . $key;
        $_SESSION[$sessionKey] = $value;
        return $this;
    }

    /**
     * Erases the values stored with the given key.
     *
     * @param string $key The key used to store the value in session.
     *
     * @return self Self-instance for
     *   method call chains.
     * @SuppressWarnings(PHPMD)
     */
    public function erase($key): self
    {
        $sessionKey = $this->prefix . $key;
        unset($_SESSION[$sessionKey]);
        return $this;
    }

    /**
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD)
     */
    private function fetchSession(): array
    {
        return $_SESSION;
    }
}
