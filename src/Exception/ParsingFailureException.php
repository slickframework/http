<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Exception;

use RuntimeException;
use Slick\Http\Exception;

/**
 * This exception is thrown when a parse object fails to parse its contents
 *
 * @package Slick\Http\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class ParsingFailureException extends RuntimeException implements Exception
{

}