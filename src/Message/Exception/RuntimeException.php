<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Message\Exception;

use RuntimeException as PhpRuntimeException;
use Slick\Http\Message\MessageException;

/**
 * Class RuntimeException
 *
 * @package Slick\Http\Message\Exception
 */
class RuntimeException extends PhpRuntimeException implements MessageException
{

}
