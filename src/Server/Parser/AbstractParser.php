<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Server\Parser;

use Psr\Http\Message\StreamInterface;

/**
 * Abstract parser class where all common logic will live in
 *
 * @package Slick\Http\Server\Parser
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
abstract class AbstractParser
{

    /**
     * @var StreamInterface The content stream
     */
    protected $content;

    /**
     * Sets the content to be parsed
     *
     * @param StreamInterface $content
     *
     * @return self
     */
    public function setContent(StreamInterface $content)
    {
        $this->content = $content;
        return $this;
    }
}