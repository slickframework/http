<?php

declare(strict_types=1);

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

use Psr\Http\Message\ServerRequestInterface;
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
class Request extends HttpRequest implements ServerRequestInterface
{
    /**
     * @var array<string, mixed>
     */
    private array $server = [];

    /**
     * @var array<string, mixed>
     */
    private array $cookies = [];

    /**
     * @var array<string, mixed>
     */
    private array $queryParams = [];

    /**
     * @var null|UploadedFile[]
     */
    private ?array $uploadedFiles = null;

    /**
     * @var mixed
     */
    private mixed $parsedBody = '';

    /**
     * @var array<string, mixed>
     */
    private array $attributes = [];

    /**
     * Creates an HTTP Server Request Message
     *
     * @param string|null              $method
     * @param string|UriInterface|null $target
     * @param string|StreamInterface   $body
     * @param array<string, string>    $headers
     */
    public function __construct(
        ?string $method = null,
        UriInterface|string|null $target = null,
        string|StreamInterface|null $body = null,
        array $headers = []
    ) {
        $method = null === $method
            ? $this->getServerParams()['REQUEST_METHOD']
            : $method;

        $body = null === $body
            ? $this->getPhpInputStream()
            : $body;

        parent::__construct($method, $target, $body, $headers);
        $this->loadHeaders();

        $this->setUri(RequestUriFactory::create($this));
    }

    /**
     * Retrieve server parameters.
     *
     * Retrieves data related to the incoming request environment,
     * typically derived from PHP's $_SERVER super-global.
     *
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD)
     */
    public function getServerParams(): array
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
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD)
     */
    public function getCookieParams(): array
    {
        if (! $this->cookies) {
            $this->cookies = $_COOKIE;
        }
        return $this->cookies;
    }

    /**
     * Return an instance with the specified cookies.
     *
     * @param array<string, mixed> $cookies Array of key/value pairs representing cookies.
     * @return Request
     */
    public function withCookieParams(array $cookies): ServerRequestInterface
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
     * @return array<string, mixed>
     */
    public function getQueryParams(): array
    {
        if (! $this->queryParams) {
            $this->queryParams = $this->detectQueryParams();
        }
        return $this->queryParams;
    }

    /**
     * Return an instance with the specified query string arguments.
     *
     * @param array<string, mixed> $query Array of query string arguments, typically from
     *     $_GET.
     *
     * @return Request
     */
    public function withQueryParams(array $query): ServerRequestInterface
    {
        $request = clone $this;
        $request->queryParams = $query;
        return $request;
    }

    /**
     * Retrieve normalized file upload data.
     *
     * @return UploadedFile[]
     */
    public function getUploadedFiles(): array
    {
        if (null === $this->uploadedFiles) {
            $this->uploadedFiles = UploadedFilesFactory::createFiles();
        }
        return $this->uploadedFiles;
    }

    /**
     * Create a new instance with the specified uploaded files.
     *
     * @param array<array<UploadedFile>>|array<UploadedFile> $uploadedFiles An array tree of
     * UploadedFileInterface instances.
     * @return Request
     *
     * @throws InvalidArgumentException if an invalid structure is provided.
     */
    public function withUploadedFiles(array $uploadedFiles): ServerRequestInterface
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
     * @return array<string, mixed>
     * @SuppressWarnings(PHPMD)
     */
    private function detectQueryParams(): array
    {
        $uri = new Uri('https://example.org' .$this->getRequestTarget());
        parse_str($uri->getQuery(), $params);
        return array_merge($_GET, $params);
    }

    /**
     * Creates a stream from php input stream
     *
     * @return TextStream|StreamInterface
     */
    private function getPhpInputStream(): TextStream|StreamInterface
    {
        return new TextStream(file_get_contents('php://input'));
    }

    /**
     * Check if a provided files array is valid
     *
     * @param array<UploadedFile|mixed>|array<array<UploadedFile|mixed>> $files
     *
     * @return bool
     */
    private function checkUploadedFiles(array $files): bool
    {
        $valid = true;

        foreach ($files as $file) {
            if (\is_array($file)) {
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
     * @SuppressWarnings(PHPMD)
     */
    private function loadHeaders(): void
    {
        foreach ($_SERVER as $key => $value) {
            $subset = substr($key, 0, 5);
            if ($subset <> 'HTTP_' && $subset <> 'CONTE') {
                continue;
            }
            $header = str_replace(
                ' ',
                '-',
                ucwords(
                    str_replace(['http_', '_'], ['', ' '], strtolower($key))
                )
            );
            $this->headers[$this->headerKey($header)] = [$value];
        }
    }

    /**
     * Retrieve any parameters provided in the request body.
     *
     * @return null|array<string, mixed>|object The deserialized body parameters, if any.
     *     These will typically be an array or object.
     */
    public function getParsedBody(): object|array|null
    {
        if (! $this->parsedBody) {
            $parser = new BodyParser($this->getHeaderLine('Content-Type'));
            $this->parsedBody = $parser->parse($this->getBody());
        }
        return $this->parsedBody;
    }

    /**
     * Return an instance with the specified body parameters.
     *
     * @param mixed $data The deserialized body data. This will
     *     typically be in an array or object.
     *
     * @return Request
     * @throws InvalidArgumentException if an unsupported argument type is
     *     provided.
     */
    public function withParsedBody(mixed $data): ServerRequestInterface
    {
        if (! \is_null($data) &&
            ! \is_array($data) &&
            ! \is_object($data)
        ) {
            throw new InvalidArgumentException(
                "Only NULL, array or Object types could be used to ".
                "create a new server request message with parsed body."
            );
        }

        $request = clone $this;
        $request->parsedBody = $data;
        return $request;
    }

    /**
     * Retrieve attributes derived from the request.
     *
     * The request "attributes" may be used to allow injection of any
     * parameters derived from the request: e.g., the results of path
     * match operations; the results of decrypting cookies; the results of
     * deserializing non-form-encoded message bodies; etc. Attributes
     * will be application- and request-specific, and CAN be mutable.
     *
     * @return array<string, mixed> Attributes derived from the request.
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Return an instance with the specified derived request attribute.
     *
     * This method allows setting a single derived request attribute as
     * described in getAttributes().
     *
     * @param string $name The attribute name.
          * @param mixed $value The value of the attribute.
     *
     * @return Request
     *@see getAttributes()
     */
    public function withAttribute(string $name, $value): ServerRequestInterface
    {
        $request = clone $this;
        $request->attributes[$name] = $value;
        return $request;
    }

    /**
     * Retrieve a single derived request attribute.
     *
     * Retrieves a single derived request attribute as described in
     * getAttributes(). If the attribute has not been previously set, returns
     * the default value as provided.
     *
     * @see getAttributes()
     * @param string $name The attribute name.
     * @param mixed $default Default value to return if the attribute does not exist.
     * @return mixed
     */
    public function getAttribute($name, $default = null): mixed
    {
        if (\array_key_exists($name, $this->attributes)) {
            $default = $this->attributes[$name];
        }
        return $default;
    }

    /**
     * Return an instance that removes the specified derived request attribute.
     *
     * This method allows removing a single derived request attribute as
     * described in getAttributes().
     *
     * @param string $name The attribute name.
          *
     * @return Request
     *@see getAttributes()
     */
    public function withoutAttribute(string $name): ServerRequestInterface
    {
        $request = clone $this;
        if (\array_key_exists($name, $request->attributes)) {
            unset($request->attributes[$name]);
        }
        return $request;
    }
}
