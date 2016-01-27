<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\PhpEnvironment;

use Psr\Http\Message\ServerRequestInterface;
use Slick\Http\Server\Request as ServerRequest;
use Slick\Http\Stream;

/**
 * Request typical server request in a PHP environment
 *
 * @package Slick\Http\PhpEnvironment
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Request extends ServerRequest implements ServerRequestInterface
{

    /**
     * Creates a request with CGI and/or PHP environment data
     */
    public function __construct()
    {
        $this->serverParams = $_SERVER;
        $this->cookieParams = $_COOKIE;
        $this->queryParams = $_GET;

        $method = isset($_SERVER['REQUEST_METHOD'])
            ? $_SERVER['REQUEST_METHOD']
            : null ;

        $this->uri = ServerRequestUri::parse($this)->getUri();

        parent::__construct(
            $method,
            null,
            ServerHeaders::get($this),
            $this->getPhpInputStream()
        );
    }

    /**
     * Creates a stream from php input stream
     *
     * @return Stream
     */
    private function getPhpInputStream()
    {
        $input = fopen('php://input', 'r');
        $temp = fopen('php://temp', 'r+');
        stream_copy_to_stream($input, $temp);
        return new Stream($temp);
    }
}
