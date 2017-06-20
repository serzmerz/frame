<?php

namespace Serz\Framework\DI;


/**
 * Class Injector
 * @package Serz\Framework\DI
 */
class Injector
{

    /**
     * @var \Auryn\Injector
     */
    public static $injector;

    /**
     * @var null
     */
    protected static $instance = null;

    /**
     * @return Injector
     */
    public static function getInstance(): self
    {
        if (empty(self::$instance)) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * not clone
     */
    private function __clone()
    {
    }

    /**
     * Injector constructor.
     */
    private function __construct()
    {
        self::$injector = new \Auryn\Injector;
    }

    /**
     * make some class
     * @param $classname
     * @return mixed
     */
    public static function make($classname)
    {
        return self::$injector->make($classname);
    }

    /**
     * define depedensies
     * @param $classname
     * @param $params
     */
    public static function define($classname, $params)
    {
        self::$injector->define($classname, $params);
    }
}