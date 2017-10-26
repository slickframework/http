<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use Slick\Http\Message\Exception\InvalidArgumentException;
use Slick\Http\Message\Request as HttpRequest;
use Slick\Http\Message\Stream\TextStream;
use Slick\Http\Message\Uri;

/**
 * Request
 *
 * @package Slick\Http\Message\Server
*/
class Request extends HttpRequest
{

    /**
     * @var array
     */
    private $server;

    /**
     * @var array
     */
    private $cookies;

    /**
     * @var array
     */
    private $queryParams;

    /**
     * @var UploadedFile[]
     */
    private $uploadedFiles;

    /**
     * Creates an HTTP Server Request Message
     *
     * @param string                   $method
     * @param string|StreamInterface   $body
     * @param null|string|UriInterface $target
     * @param array                    $headers
     */
    public function __construct($method = null, $target = null, $body = '', array $headers = [])
    {
        $method = null === $method
            ? $this->getServerParams()['REQUEST_METHOD']
            : $method;

        $body = null === $body
            ? $this->getPhpInputStream()
            : $body;

        parent::__construct($method, $target, $body, $headers);
        $this->loadHeaders();
    }

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER superglobal.
     *
     * @return array
     */
    public function getServerParams()
    {
        if (! $this->server) {
            $this->server = $_SERVER;
        }
        return $this->server;
    }

    /**
     * Retrieve cookies.
     *
     * Retrieves cookies sent by the client to the server.
     *
     * @return array
     */
    public function getCookieParams()
    {
        if (! $this->cookies) {
            $this->cookies = $_COOKIE;
        }
        return $this->cookies;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * @param array $cookies Array of key/value pairs representing cookies.
     * @return Request
     */
    public function withCookieParams(array $cookies)
    {
        $request = clone $this;
        $request->cookies = $cookies;
        return $request;
    }

    /**
     * Retrieve query string arguments.
     *
     * Retrieves the deserialized query string arguments, if any.
     *
     * @return array
     */
    public function getQueryParams()
    {
        if (! $this->queryParams) {
            $this->queryParams = $this->detectQueryParams();
        }
        return $this->queryParams;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * @param array $query Array of query string arguments, typically from
     *     $_GET.
     *
     * @return Request
     */
    public function withQueryParams(array $query)
    {
        $request = clone $this;
        $request->queryParams = $query;
        returN $request;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return UploadedFile[]
     */
    public function getUploadedFiles()
    {
        if (null === $this->uploadedFiles) {
            $this->uploadedFiles = UploadedFilesFactory::createFiles();
        }
        return $this->uploadedFiles;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * @param array $uploadedFiles An array tree of UploadedFileInterface instances.
     * @return Request
     *
     * @throws InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles)
    {
        if (! $this->checkUploadedFiles($uploadedFiles)) {
            throw new InvalidArgumentException(
                "The uploaded files array given has at least one leaf that is not ".
                "an UploadedFile object."
            );
        }

        $request = clone $this;
        $request->uploadedFiles = $uploadedFiles;
        return $request;
    }


    /**
     * Detects the query params from server and/or request URI
     *
     * @return array
     */
    private function detectQueryParams()
    {
        $uri = new Uri('http://example.org'.$this->getRequestTarget());
        parse_str($uri->getQuery(), $params);
        return array_merge($_GET, $params);
    }

    /**
     * Creates a stream from php input stream
     *
     * @return StreamInterface
     */
    private function getPhpInputStream()
    {
        $stream = new TextStream(file_get_contents('php://input'));
        return $stream;
    }

    /**
     * Check if provided files array is valid
     *
     * @param array $files
     *
     * @return bool
     */
    private function checkUploadedFiles(array $files)
    {
        $valid = true;

        foreach ($files as $file) {
            if (is_array($file)) {
                $valid = $this->checkUploadedFiles($files);
                break;
            }

            if (! $file instanceof UploadedFile) {
                $valid = false;
                break;
            }
        }

        return $valid;
    }

    /**
     * Loads the headers form request
     *
     * @param array $customHeaders
     */
    private function loadHeaders()
    {
        foreach($_SERVER as $key => $value) {
            if (substr($key, 0, 5) <> 'HTTP_') {
                continue;
            }
            $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
            $this->headers[$this->headerKey($header)] = [$value];
        }
    }


}