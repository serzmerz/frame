<?php

namespace Serz\Framework;

use Serz\Framework\DI\Conteiner;
use Serz\Framework\DI\Injector;
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
     * Application constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        Conteiner::set("config", $config);
        Conteiner::set("request", Request::getRequest());
        Injector::getInstance();
    }

    /**
     * run routing
     */
    public function start()
    {
        try {
            //$router = new Router($this->config);
            Injector::define(Router::class, [':config' => Conteiner::get("config")]);
            $router = Injector::make(Router::class);
            $route = $router->getRoute(Conteiner::get("request"));
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

                $controller = $reflectionClass->newInstanceArgs(array(Conteiner::get("config")["path_to_views"]));
                $reflectionMethod = $reflectionClass->getMethod($route_action);

                $middlewareRunner = new MiddlewareRunner(
                    Conteiner::get("config")["middleware"],
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