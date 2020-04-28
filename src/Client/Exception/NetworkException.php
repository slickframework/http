<?php

/**
 * This file is part of slick/http
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Client\Exception;

use Psr\Http\Client\NetworkExceptionInterface;

/**
 * NetworkException
 *
 */
final class NetworkException extends ClientException implements NetworkExceptionInterface
{

}
