<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * Response
 *
 * @package Slick\Http\Message
*/
class Response extends Message implements ResponseInterface
{
    /**
     * @var int
     */
    private $status;

    /**
     * @var string
     */
    private $reasonPhrase;

    /**
     * Creates an HTTP Response message
     *
     * @param int                    $status
     * @param string|StreamInterface $body
     * @param array                  $headers
     */
    public function __construct($status, $body = '', array $headers = [])
    {
        parent::__construct($body);

        $this->setStatus($status);

        foreach ($headers as $name => $header) {
            $this->headers[$name] = [$header];
        }
    }

    /**
     * Gets the response status code.
     *
     * The status code is a 3-digit integer result code of the server's attempt
     * to understand and satisfy the request.
     *
     * @return int Status code.
     */
    public function getStatusCode()
    {
        return $this->status;
    }

    /**
     * Return an instance with the specified status code and, optionally, reason phrase.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @param int $code The 3-digit integer result code to set.
     * @param string $reasonPhrase The reason phrase to use with the
     *     provided status code; if none is provided, implementations MAY
     *     use the defaults as suggested in the HTTP specification.
     * @return static
     * @throws \InvalidArgumentException For invalid status code arguments.
     */
    public function withStatus($code, $reasonPhrase = '')
    {
        $response = clone $this;
        $response->setStatus($code, $reasonPhrase);
        return $response;
    }

    /**
     * Gets the response reason phrase associated with the status code.
     *
     * @link http://tools.ietf.org/html/rfc7231#section-6
     * @link http://www.iana.org/assignments/http-status-codes/http-status-codes.xhtml
     * @return string Reason phrase; must return an empty string if none present.
     */
    public function getReasonPhrase()
    {
        return $this->reasonPhrase;
    }

    /**
     * Sets the response status code
     *
     * @param int    $status
     * @param string $reasonPhrase
     */
    private function setStatus($status, $reasonPhrase = '')
    {
        HttpCodes::check($status);
        $this->status = intval($status, 10);
        if ($reasonPhrase === '') {
            $this->reasonPhrase = HttpCodes::reasonPhraseFor($this->status);
        }
    }
}
