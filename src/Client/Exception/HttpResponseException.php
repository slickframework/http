<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Client\Exception;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;
use Slick\Http\Exception;
use Throwable;

/**
 * Http Response Exception
 *
 * @package Slick\Http\Client\Exception
 */
abstract class HttpResponseException extends RuntimeException implements Exception
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var ResponseInterface
     */
    private $response;

    /**
     * Creates an Http Response Exception
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param Throwable|null    $previous
     */
    public function __construct(
        RequestInterface $request,
        ResponseInterface $response,
        Throwable $previous = null
    ) {
        parent::__construct($response->getReasonPhrase(), $response->getStatusCode(), $previous);
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * The request made
     *
     * @return RequestInterface
     */
    public function request()
    {
        return $this->request;
    }

    /**
     * The response
     *
     * @return ResponseInterface
     */
    public function response()
    {
        return $this->response;
    }
}
