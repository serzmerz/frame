<?php

namespace serz\Framework;

use serz\Framework\Request\Request;
use serz\Framework\Router\Exceptions\InvalidRouteArgumentException;
use serz\Framework\Router\Exceptions\InvalidRouteNameException;
use serz\Framework\Router\Exceptions\RouteNotFoundException;
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
        try {

            $router = new Router($this->config['routes']);

            $request = Request::getRequest();

            $route = $router->getRoute($request);

            // debug($router->getLink("single_product",["id" =>3]));

        } catch (RouteNotFoundException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteNameException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteArgumentException $e) {
            echo $e->getMessage();
        }

    }

}