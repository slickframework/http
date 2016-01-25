<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Uri\Filter;

use Slick\Filter\StaticFilter;

/**
 * Filter Trait
 *
 * @package Slick\Http\Uri\Filter
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
trait FilterTrait
{

    /**
     * @var array Available filters
     */
    public $filters = [
        'fragment'     => 'Slick\Http\Uri\Filter\Fragment',
        'path'         => 'Slick\Http\Uri\Filter\Path',
        'query'        => 'Slick\Http\Uri\Filter\Query',
        'queryOrFrame' => 'Slick\Http\Uri\Filter\QueryOrFrame',
    ];

    /**
     * @var bool Filter factory update flag
     */
    private static $updated = false;

    /**
     * Sets the filter factory class paths
     */
    private function update()
    {
        if (!self::$updated) {
            foreach ($this->filters as $alias => $class) {
                StaticFilter::$filters[$alias] = $class;
            }
        }
    }

    /**
     * @param string $alias Filter name
     * @param mixed $value The value to filter
     *
     * @return mixed Filter output
     */
    protected function filter($alias, $value) {
        $this->update();
        return StaticFilter::filter($alias, $value);
    }
}