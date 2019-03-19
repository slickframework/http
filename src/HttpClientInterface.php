<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Psr\Http\Message\RequestInterface;
use React\Promise\PromiseInterface;

/**
 * HTTP Client Interface
 *
 * @package Slick\Http
 */
interface HttpClientInterface
{

    /**
     * Send out an HTTP requests returning a promise
     *
     * @param RequestInterface $request
     *
     * @return PromiseInterface
     */
    public function send(RequestInterface $request);
}
