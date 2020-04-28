<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Client\Exception;

use Exception;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Message\RequestInterface;
use Throwable;

/**
 * ClientException
 *
 * @package Slick\Http\Client\Exception
 */
abstract class ClientException extends Exception implements ClientExceptionInterface
{

    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * Creates a NetworkException
     *
     * @param RequestInterface $request
     * @param string $message
     */
    public function __construct(RequestInterface $request, $message = "")
    {
        parent::__construct($message);
        $this->request = $request;
    }

    /**
     * @inheritDoc
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}