<?php

/**
 * Mocked CURL functions
 * @param $resource
 * @param array $options
 */

namespace Slick\Http\Client;

abstract class CurlState
{
    public static $resource;
    public static $options = [];
    public static $error = 0;

    public static $properties = [
        CURLINFO_HEADER_SIZE => 59,
        CURLINFO_HTTP_CODE => 200
    ];

    public static $message = <<<EOM
HTTP/1.1 200 OK
Content-Type: text/plain
Content-Length: 12

Hello world!
EOM;

}

function curl_setopt_array($resource, array $options = [])
{
    CurlState::$resource = $resource;
    foreach ($options as $name => $option) {
        CurlState::$options[$name] = $option;
    }
}

function curl_errno($resource)
{
    CurlState::$resource = $resource;
    return CurlState::$error;
}

function curl_exec($resource)
{
    CurlState::$resource = $resource;
    return CurlState::$message;
}

function curl_getinfo($resource, $property)
{
    CurlState::$resource = $resource;
    return CurlState::$properties[$property];
}