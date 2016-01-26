<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Psr\Http\Message\MessageInterface;
use Psr\Http\Message\StreamInterface;
use Slick\Http\Exception\InvalidArgumentException;

class Message implements MessageInterface
{

    /**
     * @var string HTTP protocol version
     */
    protected $protocolVersion = "1.1";

    /**
     * @var array Message headers
     */
    protected $headers = [];

    /**
     * @var StreamInterface
     */
    protected $body;

    /**
     * @var array List of available header names
     */
    private $headerNames = [];

    /**
     * Message constructor.
     * @param string $body
     * @param array $headers
     */
    public function __construct($body = 'php://memory', array $headers = [])
    {
        $this->withBody(new Stream($body));
        foreach ($headers as $name => $value) {
            $this->withHeader($name, $value);
        }
    }

    /**
     * Retrieves the HTTP protocol version as a string.
     *
     * @return string HTTP protocol version.
     */
    public function getProtocolVersion()
    {
        return $this->protocolVersion;
    }

    /**
     * Return an instance with the specified HTTP protocol version.
     *
     * The version string MUST contain only the HTTP version number (e.g.,
     * "1.1", "1.0").
     *
     * @param string $version HTTP protocol version
     * @return self
     */
    public function withProtocolVersion($version)
    {
        $allowedValues = ['1.0', '1.1'];
        if (!in_array($version, $allowedValues)) {
            throw new InvalidArgumentException(
                "HTTP version must be one of '1.0' or '1.1'. {$version} is ".
                "not a acceptable HTTP protocol version."
            );
        }
        $this->protocolVersion = $version;
        return $this;
    }

    /**
     * Retrieves all message header values.
     *
     * The keys represent the header name as it will be sent over the wire, and
     * each value is an array of strings associated with the header.
     *
     *     // Represent the headers as a string
     *     foreach ($message->getHeaders() as $name => $values) {
     *         echo $name . ": " . implode(", ", $values);
     *     }
     *
     *     // Emit headers iteratively:
     *     foreach ($message->getHeaders() as $name => $values) {
     *         foreach ($values as $value) {
     *             header(sprintf('%s: %s', $name, $value), false);
     *         }
     *     }
     *
     * While header names are not case-sensitive, getHeaders() will preserve the
     * exact case in which headers were originally specified.
     *
     * @return array Returns an associative array of the message's headers. Each
     *     key MUST be a header name, and each value MUST be an array of strings
     *     for that header.
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Checks if a header exists by the given case-insensitive name.
     *
     * @param string $name Case-insensitive header field name.
     * @return bool Returns true if any header names match the given header
     *     name using a case-insensitive string comparison. Returns false if
     *     no matching header name is found in the message.
     */
    public function hasHeader($name)
    {
        $normalized = strtolower($name);
        return in_array($normalized, array_keys($this->headerNames));
    }

    /**
     * Retrieves a message header value by the given case-insensitive name.
     *
     * This method returns an array of all the header values of the given
     * case-insensitive header name.
     *
     * If the header does not appear in the message, this method will return an
     * empty array.
     *
     * @param string $name Case-insensitive header field name.
     * @return string[] An array of string values as provided for the given
     *    header. If the header does not appear in the message, this method MUST
     *    return an empty array.
     */
    public function getHeader($name)
    {
        $value = [];
        if ($this->hasHeader($name)) {
            $normalized = strtolower($name);
            $name = $this->headerNames[$normalized];
            $value = $this->headers[$name];
        }
        return $value;
    }

    /**
     * Retrieves a comma-separated string of the values for a single header.
     *
     * This method returns all of the header values of the given
     * case-insensitive header name as a string concatenated together using
     * a comma.
     *
     * NOTE: Not all header values may be appropriately represented using
     * comma concatenation. For such headers, use getHeader() instead
     * and supply your own delimiter when concatenating.
     *
     * If the header does not appear in the message, this method will return
     * an empty string.
     *
     * @param string $name Case-insensitive header field name.
     * @return string A string of values as provided for the given header
     *    concatenated together using a comma. If the header does not appear in
     *    the message, this method will return an empty string.
     */
    public function getHeaderLine($name)
    {
        $values = $this->getHeader($name);
        return implode(',', $values);
    }

    /**
     * Return an instance with the provided value replacing the specified header.
     *
     * While header names are case-insensitive, the casing of the header will
     * be preserved by this function, and returned from getHeaders().
     *
     * @param string $name Case-insensitive header field name.
     * @param string|string[] $value Header value(s).
     * @return self|$his
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withHeader($name, $value)
    {
        $value = $this->prepareHeaderValues($value);
        $normalized = strtolower($name);
        if ($this->hasHeader($name)) {
            $name = $this->headerNames[$normalized];
        }
        $this->headerNames[$normalized] = $name;
        $this->headers[$name] = $value;
        return $this;
    }

    /**
     * Return an instance with the specified header appended with the given value.
     *
     * Existing values for the specified header will be maintained. The new
     * value(s) will be appended to the existing list. If the header did not
     * exist previously, it will be added.
     *
     * @param string $name Case-insensitive header field name to add.
     * @param string|string[] $value Header value(s).
     * @return self
     * @throws \InvalidArgumentException for invalid header names or values.
     */
    public function withAddedHeader($name, $value)
    {
        $value = $this->prepareHeaderValues($value);
        if ($this->hasHeader($name)) {
            $value = array_merge($this->getHeader($name), $value);
        }

        return $this->withHeader($name, $value);
    }

    /**
     * Return an instance without the specified header.
     *
     * Header resolution will be done without case-sensitivity.
     *
     * @param string $name Case-insensitive header field name to remove.
     * @return self|$this
     */
    public function withoutHeader($name)
    {
        $normalized = strtolower($name);
        if ($this->hasHeader($name)) {
            $name = $this->headerNames[$normalized];
            unset($this->headerNames[$normalized]);
            unset($this->headers[$name]);
        }
        return $this;
    }

    /**
     * Gets the body of the message.
     *
     * @return StreamInterface Returns the body as a stream.
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Return an instance with the specified message body.
     *
     * The body MUST be a StreamInterface object.
     *
     * This method MUST be implemented in such a way as to retain the
     * immutability of the message, and MUST return a new instance that has the
     * new body stream.
     *
     * @param StreamInterface $body Body.
     * @return self|$this
     * @throws \InvalidArgumentException When the body is not valid.
     */
    public function withBody(StreamInterface $body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Returns the header values as an array
     *
     * @param string|array $values
     *
     * @return array
     */
    protected function prepareHeaderValues($values)
    {
        if (is_string($values)) {
            $values = [$values];
        }
        // TODO: Validate $value as an array of strings
        return $values;
    }
}