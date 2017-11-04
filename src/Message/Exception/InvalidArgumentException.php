<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Exception;

use InvalidArgumentException as PhpInvalidArgumentException;
use Slick\Http\Message\MessageException;

/**
 * Class InvalidArgumentException
 * @package Slick\Http\Message\Exception
 */
class InvalidArgumentException extends PhpInvalidArgumentException implements MessageException
{

}