<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Exception;

use LogicException;
use Slick\Http\Exception;

/**
 * Exception thrown when a trying to parse content on a parse object without
 * setting the content stream.
 *
 * @package Slick\Http\Exception
 */
class MissingContentException extends LogicException implements Exception
{

}