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
 * Used by factory when it cannot realize that parser to create
 *
 * @package Slick\Http\Server\Parser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class NullParser extends AbstractParser implements ParserInterface
{

    /**
     * Parses the current content and returns its data
     *
     * @return null|array|object The deserialized data from current content
     */
    public function parse()
    {
        return null;
    }
}