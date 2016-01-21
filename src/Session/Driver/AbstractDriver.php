<?php

/**
 * This file is part of slick/http package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Http\Session\Driver;

use Slick\Common\Base;

/**
 * Session driver, base class for all session drivers
 *
 * @package Slick\Http\Session\Driver
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 *
 * @property string $prefix
 * @property string $name
 * @property string $domain
 * @property int    $lifetime
 */
abstract class AbstractDriver extends Base
{

    /**
     * @readwrite
     * @var string Session prefix on key names
     */
    protected $prefix = "slick_";

    /**
     * @readwrite
     * @var string Session cookie name
     */
    protected $name = 'SLICKSID';

    /**
     * @readwrite
     * @var string Session cookie domain
     */
    protected $domain = null;

    /**
     * @readwrite
     * @var integer Session cookie lifetime
     */
    protected $lifetime = 0;

    /**
     * Initialize the session driver
     *
     * @param array|object $options
     */
    public function __construct($options = [])
    {
        parent::__construct($options);
        $this->initialize();
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, $default = null)
    {
        $prefix = $this->prefix;
        if (isset($_SESSION[$prefix.$key])) {
            $default = $_SESSION[$prefix.$key];
        }
        return $default;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $value)
    {
        $prefix = $this->prefix;
        $_SESSION[$prefix.$key] = $value;
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function erase($key)
    {
        $prefix = $this->prefix;
        unset($_SESSION[$prefix.$key]);
        return $this;
    }

    /**
     * Driver initialization callback
     *
     * @return mixed
     */
    abstract protected function initialize();
}
