<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * User: fsilva
 * Date: 01-11-2017
 * Time: 21:05
 */

namespace Slick\Http\Server\Exception;

use InvalidArgumentException as PhpException;
use Slick\Http\Exception;

class InvalidArgumentException extends PhpException implements Exception
{
}
