<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Slick\Http\Session\Driver\NullDriver;
use Slick\Http\Session\Driver\ServerDriver;
use Slick\Http\Session\Exception\ClassNotFoundException;
use Slick\Http\Session\Exception\InvalidDriverClassException;
use Slick\Http\Session\SessionDriverInterface;

/**
 * Session factory class
 *
 * @package Slick\Http
*/
final class Session
{

    const DRIVER_NULL   = NullDriver::class;
    const DRIVER_SERVER = ServerDriver::class;

    /**
     * @var string
     */
    private $driverClass;

    /**
     * @var array
     */
    private $options;

    /**
     * Creates a Session factory
     *
     * @param string $driverClass
     * @param array  $options
     */
    public function __construct($driverClass, array $options = [])
    {
        $this->driverClass = $driverClass;
        $this->options = $options;
    }

    /**
     * Creates the session driver with provided options
     *
     * @param string $driverClass
     * @param array  $options
     *
     * @return SessionDriverInterface
     *
     * @throws ClassNotFoundException if class does not exists
     * @throws InvalidDriverClassException if class does not implement the SessionDriverInterface
     */
    public static function create($driverClass, array $options = [])
    {
        $session = new Session($driverClass, $options);
        return $session->initialize();
    }

    /**
     * Initializes a new session driver
     *
     * @return SessionDriverInterface
     *
     * @throws ClassNotFoundException if class does not exists
     * @throws InvalidDriverClassException if class does not implement the SessionDriverInterface
     */
    public function initialize()
    {
        $this->checkClassExistence();

        $this->checkClassType();

        $className = $this->driverClass;
        return new $className($this->options);
    }

    /**
     * Checks if current driver class implements SessionDriverInterface
     */
    private function checkClassType()
    {
        if (! is_subclass_of($this->driverClass, SessionDriverInterface::class)) {
            throw new InvalidDriverClassException(
                sprintf(
                    "Session driver classes must implement %s interface.",
                    SessionDriverInterface::class
                )
            );
        }
    }

    /**
     * Check if driver class exists
     */
    private function checkClassExistence()
    {
        if (!class_exists($this->driverClass)) {
            throw new ClassNotFoundException(
                "Session driver class '{$this->driverClass}'' does not exists."
            );
        }
    }
}