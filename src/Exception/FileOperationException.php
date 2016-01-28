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
 * File Operation Exception, raised when an error occur during file
 * read/copy operations.
 *
 * @package Slick\Http\Exception
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class FileOperationException extends RuntimeException implements Exception
{

}
