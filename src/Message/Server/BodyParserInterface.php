<?php

/**
 * This file is part of Http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Server;

/**
 * BodyParserInterface
 *
 * @package Slick\Http\Message\Server
 */
interface BodyParserInterface
{

    /**
     * Parses the provided
     *
     * @return mixed
     */
    public function parse();
}