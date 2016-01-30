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
 * URI Scheme filter
 *
 * @package Slick\Http\Uri\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class Scheme implements FilterInterface
{

    /**
     * Array indexed by valid scheme names to their corresponding ports.
     * @var int[]
     */
    public static $normalSchemes = [
        'http'  => 80,
        'https' => 443,
    ];

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
        $value = strtolower($value);
        $value = preg_replace('#:(//)?$#', '', $value);
        if (empty($value)) {
            return '';
        }
        if (! array_key_exists($value, self::$normalSchemes)) {
            throw new Exception\CannotFilterValueException(sprintf(
                'Unsupported scheme "%s"; must be any empty string or '.
                'in the set (%s)',
                $value,
                implode(', ', array_keys(self::$normalSchemes))
            ));
        }
        return $value;
    }
}