<?php

declare(strict_types=1);

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
    private RequestInterface $request;

    /**
     * Creates a NetworkException
     *
     * @param RequestInterface $request
     * @param string $message
     */
    public function __construct(RequestInterface $request, string $message = "")
    {
        parent::__construct($message);
        $this->request = $request;
    }

    /**
     * Retrieves the associated request instance.
     *
     * @return RequestInterface The request associated with this instance.
     */
    public function getRequest(): RequestInterface
    {
        return $this->request;
    }
}
