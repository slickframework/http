<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Psr\Http\Message\ResponseInterface;

/**
 * Middleware Runner Interface: manages the execution of a middleware
 * decorator objects.
 *
 * @package Slick\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface MiddlewareRunnerInterface
{

    /**
     * Adds a middleware object to the stack
     *
     * @param MiddlewareInterface $middleware
     *
     * @return self|$this|MiddlewareRunnerInterface
     */
    public function add(MiddlewareInterface $middleware);

    /**
     * Runs all the stack and return the response
     *
     * @return ResponseInterface
     */
    public function run();

    /**
     * Sets a full list of middleware objects tho this runner
     *
     * @param MiddlewareCollectionInterface $objects
     *
     * @return self|$this|MiddlewareRunnerInterface
     */
    public function setMiddlewareCollection(
        MiddlewareCollectionInterface $objects
    );
}
