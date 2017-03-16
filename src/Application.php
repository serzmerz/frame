<?php

namespace serz\Framework;

use serz\Framework\Request\Exceptions\InvalidQueryKeyException;
use serz\Framework\Request\Request;
use serz\Framework\Response\Response;
use serz\Framework\Router\Router;
use serz\Framework\Router\Exceptions\{
    InvalidRouteArgumentException,
    InvalidRouteNameException,
    RouteNotFoundException
};


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

            $route_controller = $route->getControllerName();
            $route_action = $route->getControllerAction();

            if (class_exists($route_controller)) {

                $reflectionClass = new \ReflectionClass($route_controller);

                if ($reflectionClass->hasMethod($route_action)) {

                    $controller = $reflectionClass->newInstanceArgs(array($this->config["path_to_views"]));
                    $reflectionMethod = $reflectionClass->getMethod($route_action);
                    $response = $reflectionMethod->invokeArgs($controller, $route->getVariables());

                    if ($response instanceof Response) {
                        $response->send();
                    }
                }
            }

            // debug($request->getQueryString());
            // debug($request->getParamQuery("c"));
            // debug($router->getLink("single_product",["id" =>3]));

        } catch (RouteNotFoundException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteNameException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteArgumentException $e) {
            echo $e->getMessage();
        } catch (InvalidQueryKeyException $e) {
            echo $e->getMessage();
        }


    }

}