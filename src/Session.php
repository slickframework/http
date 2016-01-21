<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http;

use Slick\Common\Base;

/**
 * Session factory class
 *
 * @package Slick\Http
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
final class Session extends Base
{

    /** Know session drivers */
    const DRIVER_SERVER = 'Slick\Http\Session\Driver\Server';

    /**
     * @readwrite
     * @var string Driver alias or FQ class name
     */
    protected $driver = self::DRIVER_SERVER;

    /**
     * @readwrite
     * @var array Driver options
     */
    protected $options = [];

    public static function create($driver = null, $options = [])
    {
        $driver = $driver ?: self::DRIVER_SERVER;
        $session = new static(['driver' => $driver, 'options' => $options]);
        return $session->initialize();
    }

    public function initialize()
    {
        return $this;
    }
}