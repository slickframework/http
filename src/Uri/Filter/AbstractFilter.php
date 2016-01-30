<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Uri\Filter;

/**
 * Abstract Filter for URI
 *
 * @package Slick\Http\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractFilter
{
    /**
     * Unreserved characters used in paths, query strings, and fragments.
     *
     * @const string
     */
    const CHAR_UNRESERVED = 'a-zA-Z0-9_\-\.~';

    /**
     * URL encode a character returned by a regex.
     *
     * @param array $matches
     * @return string
     */
    protected function urlEncodeChar(array $matches)
    {
        return rawurlencode($matches[0]);
    }
}