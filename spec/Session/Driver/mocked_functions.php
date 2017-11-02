<?php

/**
 * Mocked PHP functions to test session driver
 */

namespace Slick\Http\Session\Driver;

use spec\Slick\Http\Session\Driver\ServerDriverSpec;

function session_set_cookie_params($lifetime, $path, $domain)
{
    ServerDriverSpec::$sessionParams = compact('lifetime', 'path', 'domain');
}

function session_name($name)
{
    ServerDriverSpec::$sessionParams['name'] = $name;
}

function session_status()
{
    return ServerDriverSpec::$sessionState;
}

function session_start()
{
    return ServerDriverSpec::$sessionInitialized = true;
}