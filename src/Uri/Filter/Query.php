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
 * URI Query filter
 *
 * @package Slick\Http\Uri\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Query implements FilterInterface
{

    /**
     * Returns the result of filtering $value
     *
     * @param mixed $query
     *
     * @throws Exception\CannotFilterValueException
     *      If filtering $value is impossible.
     *
     * @return mixed
     */
    public function filter($query)
    {
        if (! empty($query) && strpos($query, '?') === 0) {
            $query = substr($query, 1);
        }
        $parts = explode('&', $query);
        foreach ($parts as $index => $part) {
            list($key, $value) = $this->splitQueryValue($part);
            if ($value === null) {
                $parts[$index] = StaticFilter::filter(QueryOrFragment::class, $key);
                continue;
            }
            $parts[$index] = sprintf(
                '%s=%s',
                StaticFilter::filter(QueryOrFragment::class, $key),
                StaticFilter::filter(QueryOrFragment::class, $value)
            );
        }
        return implode('&', $parts);
    }

    /**
     * Split a query value into a key/value tuple.
     *
     * @param string $value
     * @return array A value with exactly two elements, key and value
     */
    private function splitQueryValue($value)
    {
        $data = explode('=', $value, 2);
        if (1 === count($data)) {
            $data[] = null;
        }
        return $data;
    }
}