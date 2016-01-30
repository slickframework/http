<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\PhpEnvironment;

use Psr\Http\Message\ResponseInterface;
use Slick\Http\Server\MiddlewareRunnerInterface as ServerRunner;

/**
 * PHP Environment Server Middleware Runner Interface
 *
 * @package Slick\Http\PhpEnvironment
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface MiddlewareRunnerInterface extends ServerRunner
{

    /**
     * Runs all the stack and return the response
     *
     * @return ResponseInterface|Response
     */
    public function run();
}