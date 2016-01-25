<?php
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 25-01-2016
 * Time: 19:17
 */

namespace Slick\Http\Uri;


use Psr\Http\Message\UriInterface;

class Parser
{

    /**
     * @var string
     */
    private $string;

    /**
     * @var UriInterface
     */
    private $uri;


    public function __construct($string, UriInterface $uri)
    {
        $this->string = $string;
        $this->uri = $uri;
    }

    public static function parse($string, UriInterface $uri)
    {
        if (empty($string)) {
            return;
        }

        $parts = parse_url($string);
        if (false === $parts) {
            throw new \InvalidArgumentException(
                'The source URI string appears to be malformed'
            );
        }
        $uri->scheme    = isset($parts['scheme'])   ? $uri->filterScheme($parts['scheme']) : '';
        $uri->userInfo  = isset($parts['user'])     ? $parts['user']     : '';
        $uri->host      = isset($parts['host'])     ? $parts['host']     : '';
        $uri->port      = isset($parts['port'])     ? $parts['port']     : null;
        $uri->path      = isset($parts['path'])     ? $this->filter('path', $parts['path']) : '';
        $uri->query     = isset($parts['query'])    ? $this->filter('query', $parts['query']) : '';
        $uri->fragment  = isset($parts['fragment']) ? $this->filter('fragment', $parts['fragment']) : '';
        if (isset($parts['pass'])) {
            $uri->userInfo .= ':' . $parts['pass'];
        }
    }
}