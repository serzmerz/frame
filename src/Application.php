<?php

namespace serz\Framework;

use serz\Framework\Router\Exceptions\InvalidRouteArgumentException;
use serz\Framework\Router\Exceptions\InvalidRouteNameException;
use serz\Framework\Router\Router;

/**
 * Class Application start application
 * @package serz\Framework
 */
class Application
{

    /**
     * @var array
     * config routes
     */
    public $config = [];

    /**
     * Application constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * run routing
     */
    public function start()
    {
        try{
            $router = new Router($this->config['routes']);

            debug($router->getLink("single_product",["id" =>3]));
        }
        catch (InvalidRouteNameException $e){
            echo $e->getMessage();
        }
        catch (InvalidRouteArgumentException $e){
            echo $e->getMessage();
        }

    }

}