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
 * Simple HTTP Client
 *
 * @package Slick\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Client implements ClientInterface
{

    /**
     * @var array Client options
     */
    protected $options = [];

    /**
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * HTTP client: wrapper for Guzzle HTTP client
     *
     * @param array $options
     */
    public function __construct($options = [])
    {
        $this->options = $options;
    }

    /**
     * Send out the request and returns the server response
     *
     * @param RequestInterface $request
     *
     * @return ResponseInterface
     */
    public function send(RequestInterface $request)
    {
        $result = $this->getClient()->send($request);
        $response = new Response(
            $result->getStatusCode(),
            $result->getHeaders(),
            $result->getBody()
        );
        return $response;
    }

    /**
     * Gets internal HTTP client (Guzzle HTTP)
     *
     * @return \GuzzleHttp\Client
     */
    public function getClient()
    {
        if (null === $this->client) {
            $this->client = new \GuzzleHttp\Client($this->options);
        }
        return $this->client;
    }
}