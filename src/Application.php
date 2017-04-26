<?php

namespace Serz\Framework;

use Serz\Framework\Middleware\MiddlewareRunner;
use Serz\Framework\Exceptions\{
    AllMiddlewareNotRunException, GetNoResponseClassException, RouteActionNotFoundException, RouteClassNotFoundException
};
use Serz\Framework\Request\Exceptions\InvalidQueryKeyException;
use Serz\Framework\Request\Request;
use Serz\Framework\Response\Response;
use Serz\Framework\Router\Route;
use Serz\Framework\Router\Router;
use Serz\Framework\Router\Exceptions\{
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

            $router = new Router($this->config);
            $route = $router->getRoute(Request::getRequest());
            $this->makeRoute($route);

        } catch (RouteNotFoundException $e) {
            echo $e->getMessage();
        } catch (InvalidRouteNameException $e) {
            echo $e->getMessage();
        } catch (RouteClassNotFoundException $e) {
            echo $e->getMessage();
        } catch (RouteActionNotFoundException $e) {
            echo $e->getMessage();
        } catch (GetNoResponseClassException $e) {
            echo $e->getMessage();
        }

    }

    /**
     * Call action in controller class
     * @param Route $route
     * @throws GetNoResponseClassException
     * @throws RouteActionNotFoundException
     * @throws RouteClassNotFoundException
     */
    private function makeRoute(Route $route)
    {

        $route_controller = $route->getControllerName();
        $route_action = $route->getControllerAction();

        if (class_exists($route_controller)) {

            $reflectionClass = new \ReflectionClass($route_controller);

            if ($reflectionClass->hasMethod($route_action)) {

                $controller = $reflectionClass->newInstanceArgs(array($this->config["path_to_views"]));
                $reflectionMethod = $reflectionClass->getMethod($route_action);

                $middlewareRunner = new MiddlewareRunner(
                    $this->config["middleware"],
                    $route);

                $middlewareResponse = $middlewareRunner->runMiddlewares();
                if($middlewareResponse)
                    $response = $reflectionMethod->invokeArgs($controller, $route->getVariables());
                else throw new AllMiddlewareNotRunException("Forbidden!");
                if ($response instanceof Response) {
                    $response->send();
                } else throw new GetNoResponseClassException("Get no response class!");
            } else throw new RouteActionNotFoundException("Searching action not found!");
        } else throw new RouteClassNotFoundException("Searching class not found!");
    }

}