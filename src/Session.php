<?php

declare(strict_types=1);

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
    public const DRIVER_NULL   = NullDriver::class;
    public const DRIVER_SERVER = ServerDriver::class;

    /**
     * @var string
     */
    private string $driverClass;

    /**
     * @var array<string, mixed>
     */
    private array $options;

    /**
     * Creates a Session factory
     *
     * @param string $driverClass
     * @param array<string, mixed>  $options
     */
    public function __construct(string $driverClass, array $options = [])
    {
        $this->driverClass = $driverClass;
        $this->options = $options;
    }

    /**
     * Creates the session driver with provided options
     *
     * @param string $driverClass
     * @param array<string, mixed>  $options
     *
     * @return SessionDriverInterface
     *
     * @throws ClassNotFoundException if class does not exists
     * @throws InvalidDriverClassException if the class does not implement the SessionDriverInterface
     */
    public static function create(
        string $driverClass = self::DRIVER_SERVER,
        array $options = []
    ): SessionDriverInterface {
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
    public function initialize(): SessionDriverInterface
    {
        $this->checkClassExistence();

        $this->checkClassType();

        $className = $this->driverClass;
        /** @var SessionDriverInterface $sessionDriver */
        $sessionDriver = new $className($this->options);
        return $sessionDriver;
    }

    /**
     * Checks if the current driver class implements SessionDriverInterface
     */
    private function checkClassType(): void
    {
        if (! is_subclass_of($this->driverClass, SessionDriverInterface::class)) {
            throw new InvalidDriverClassException(
                \sprintf(
                    "Session driver classes must implement %s interface.",
                    SessionDriverInterface::class
                )
            );
        }
    }

    /**
     * Check if driver class exists
     */
    private function checkClassExistence(): void
    {
        if (!class_exists($this->driverClass)) {
            throw new ClassNotFoundException(
                "Session driver class '$this->driverClass'' does not exists."
            );
        }
    }
}
