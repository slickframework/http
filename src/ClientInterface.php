<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * HTTP Client interface
 *
 * @package Slick\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface ClientInterface
{

    /**
     * Send out the request and returns the server response
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request);

}