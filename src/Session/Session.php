<?php

namespace Serz\Framework\Session;


/**
 * Class Session
 * @package Serz\Framework\Session
 */
class Session
{
    /**
     * @return bool
     */
    public static function start(): bool
    {
        return session_start();
    }

    /**
     * destroy session
     * @return bool
     */
    public static function destroy(): bool
    {
        return session_destroy();
    }

    /**
     * get value session
     * @param $param
     * @return null
     */
    public static function get($param)
    {
        return isset($_SESSION[$param]) ? $_SESSION[$param] : null;
    }

    /**
     * set value in session
     * @param $param
     * @param $value
     */
    public static function set($param, $value)
    {
        $_SESSION[$varname] = $value;
    }
}