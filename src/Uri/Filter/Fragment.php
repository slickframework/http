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
use Slick\Filter\StaticFilter;

/**
 * URI Fragment filter
 *
 * @package Slick\Http\Uri\Filter
 * @readwrite
 */
class Fragment implements FilterInterface
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
        if (! empty($value) && strpos($value, '#') === 0) {
            $value = substr($value, 1);
        }
        return StaticFilter::filter(QueryOrFragment::class, $value);
    }
}