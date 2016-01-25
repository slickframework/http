<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Uri\Filter;

use Slick\Filter\Exception;
use Slick\Filter\FilterInterface;

/**
 * URI Path filter
 *
 * @package Slick\Http\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Path extends AbstractFilter implements FilterInterface
{

    /**
     * Returns the result of filtering $value
     *
     * @param mixed $value
     *
     * @throws Exception\CannotFilterValueException
     *      If filtering $value is impossible.
     *
     * @return mixed
     */
    public function filter($value)
    {
        $value = preg_replace_callback(
            '/(?:[^' . self::CHAR_UNRESERVED.
            ':@&=\+\$,\/;%]+|%(?![A-Fa-f0-9]{2}))/',
            [$this, 'urlEncodeChar'],
            $value
        );
        if (empty($value)) {
            // No path
            return $value;
        }
        if ($value[0] !== '/') {
            // Relative path
            return $value;
        }
        // Ensure only one leading slash, to prevent XSS attempts.
        return '/' . ltrim($value, '/');
    }
}