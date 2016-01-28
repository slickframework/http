<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server\Parser;

use Slick\Http\Server\ParserInterface;

/**
 * Class UrlEncodedPost
 * @package Slick\Http\Server\Parser
 */
class UrlEncodedPost extends AbstractParser implements ParserInterface
{

    /**
     * Parses the current content and returns its data
     *
     * @return null|array|object The deserialized data from current content
     */
    public function parse()
    {
        return $_POST;
    }
}