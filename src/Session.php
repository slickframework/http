<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Slick\Common\Base;
use Slick\Http\Exception\InvalidArgumentException;

/**
 * Session factory class
 *
 * @package Slick\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class Session extends Base
{

    /** Know session drivers */
    const DRIVER_SERVER = 'Slick\Http\Session\Driver\Server';
    const DRIVER_NULL   = 'Slick\Http\Session\Driver\NullDriver';

    /**
     * @readwrite
     * @var string Driver alias or FQ class name
     */
    protected $driver = self::DRIVER_SERVER;

    /**
     * @readwrite
     * @var array Driver options
     */
    protected $options = [];

    /**
     * Creates the session driver with provided options
     *
     * If no driver is provided the Server driver with default options
     * will be created.
     *
     * @param null|string $driver
     * @param array $options
     *
     * @throws InvalidArgumentException If the class does not exists or if
     *      class does not implements the SessionDriverInterface.
     *
     * @return SessionDriverInterface
     */
    public static function create($driver = null, $options = [])
    {
        $driver = $driver ?: self::DRIVER_SERVER;
        $session = new static(['driver' => $driver, 'options' => $options]);
        return $session->initialize();
    }

    /**
     * Creates the session driver with current options
     *
     * @throws InvalidArgumentException If the class does not exists or if
     *      class does not implements the SessionDriverInterface.
     *
     * @return SessionDriverInterface
     */
    public function initialize()
    {
        $this->checkClass($this->driver);
        $this->checkDriver($this->driver);
        return new $this->driver($this->options);
    }

    /**
     * Checks if class exists
     *
     * @param string $className
     *
     * @throws InvalidArgumentException
     *      If the class does not exists.
     */
    protected function checkClass($className)
    {
        if (!class_exists($className)) {
            throw new InvalidArgumentException(
                "{$className} does not exists."
            );
        }

    }

    /**
     * Verifies if a class implements the SessionDriverInterface interface
     *
     * @param string $class
     *
     * @throws InvalidArgumentException
     *      If the class does not implements the interface.
     */
    protected static function checkDriver($class)
    {
        if (!is_subclass_of($class, SessionDriverInterface::class)) {
            throw new InvalidArgumentException(
                "Class {$class} does not implements the ".
                "Slick\\Filter\\FilterInterface interface."
            );
        }
    }
}