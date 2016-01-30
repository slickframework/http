<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server;

use Slick\Common\Utils\CollectionInterface;

/**
 * Middleware Collection Interface
 *
 * @package Slick\Http\Server
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
interface MiddlewareCollectionInterface extends CollectionInterface
{

    /**
     * Appends a middleware object to the current list
     *
     * @param MiddlewareInterface $middleware
     *
     * @return $this|self|MiddlewareCollectionInterface
     */
    public function append(MiddlewareInterface $middleware);
}