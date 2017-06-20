<?php

namespace Serz\Framework\DI;


/**
 * Class Conteiner
 * @package Serz\Framework\DI
 */
class Conteiner
{
    /**
     * @var array
     */
    protected static $arrayService = [];

    /**
     * get some service
     * @param $name
     * @return mixed
     */
    public static function get($name)
    {
        if (!array_key_exists($name, self::$arrayService)) {
            self::set($name, Injector::make($name));
        }
        return self::$arrayService[$name];
    }

    /**
     * set some service
     * @param $name
     * @param $value
     */
    public static function set($name, $value)
    {
        self::$arrayService[$name] = $value;
    }
}