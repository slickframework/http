<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Slick\Common\Utils\Collection\AbstractCollection;

/**
 * Middleware Collection
 *
 * @package Slick\Http\Server
 * @author  Filipe Silva <slivam.filipe@gmail.com>
 */
class MiddlewareCollection extends AbstractCollection implements
    MiddlewareCollectionInterface
{

    /**
     * Appends a middleware object to the current list
     *
     * @param MiddlewareInterface $middleware
     *
     * @return $this|self|MiddlewareCollectionInterface
     */
    public function append(MiddlewareInterface $middleware)
    {
        array_push($this->data, $middleware);
        return $this;
    }

    /**
     * Offset to set
     *
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value  The value to set.
     */
    public function offsetSet($offset, $value)
    {
        $this->append($value);
    }
}
