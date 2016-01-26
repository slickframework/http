<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Uri;

use Psr\Http\Message\UriInterface;
use Slick\Http\Uri;
use Slick\Http\Uri\Filter\FilterTrait;

/**
 * URI Parser: Converts a string to a UriInterface object
 *
 * @package Slick\Http\Uri
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Parser
{

    /**
     * @var string
     */
    private $string;

    /**
     * @var UriInterface|Uri
     */
    private $uri;

    use FilterTrait;


    public function __construct($string, UriInterface $uri)
    {
        $this->string = $string;
        $this->uri = $uri;
    }

    public static function parse($string, UriInterface $uri)
    {
        $parser = new static($string, $uri);
        $parser->process();
        return $uri;
    }

    protected function process()
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


        $this->uri->scheme = $this->filter('scheme', $parts['scheme']);

        $this->uri->userInfo = isset($parts['user'])
            ? $parts['user']
            : '';

        $this->uri->host = isset($parts['host'])
            ? $parts['host']
            : '';

        $this->uri->port = isset($parts['port'])
            ? $parts['port']
            : null;

        $this->uri->path = $this->filter('path', $parts['path']);
        $this->uri->query = $this->filter('query', $parts['query']);
        $this->uri->fragment  = $this->filter('fragment', $parts['fragment']);

        if (isset($parts['pass'])) {
            $this->uri->userInfo .= ':' . $parts['pass'];
        }
    }
}