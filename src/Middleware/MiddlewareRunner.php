<?php

namespace Serz\Framework\Middleware;

use Mystore\Middleware\CheckMiddleware;
use Mystore\Middleware\IsAdminMiddleware;
use Mystore\Middleware\OldMiddleware;
use Serz\Framework\Request\Request;
use Serz\Framework\Response\JsonResponse;
use Serz\Framework\Response\Response;
use Serz\Framework\Router\Route;

/**
 * Class MiddlewareRunner
 * Run some middlewares with variables
 * @package Serz\Framework\Middleware
 */
class MiddlewareRunner
{

    /**
     * @var array middlewares and path to class
     */
    public $middlewares = [];

    /**
     * @var array variables
     */
    public $middlewaresVariables = [];

    /**
     * @var response class
     */
    public $response;

    /**
     * MiddlewareRunner constructor.
     */
    public function __construct(array $config, Route $route, Response $response)
    {
        $middlewareVariables = $route->getMiddleware();

        foreach ($config as $key => $item) {
            if (array_key_exists($key, $middlewareVariables)) {
                $check[$key] = $middlewareVariables[$key];
            }
        }

        foreach ($config as $key => $value) {
            if (array_key_exists($key, $check)) {
                $this->middlewares[$key] = $value;
                $this->middlewaresVariables[$key] = $check[$key];
            }
        }
        $this->response = $response;
    }

    /**
     * Build closure for next calling middleware
     * @param $classPath
     * @param $nextAfter
     * @param null $variables
     * @return \Closure
     */
    public function buildClosure(string $classPath, \Closure $nextAfter, array $variables = null): \Closure
    {
        return function ($request) use ($classPath, $nextAfter, $variables) {
            $middlewareClass = new $classPath();
            $variablesArray = [$request, $nextAfter];
            $variablesArray = array_merge($variablesArray, $variables);
            return call_user_func_array(array($middlewareClass, "handle"), $variablesArray);
        };
    }

    /**
     * Run all sequence middlewares
     * @return Response
     */
    public function runMiddlewares(): Response
    {

        $request = Request::getRequest();

        $lastNext = function ($request) {
            return $this->response;
        };

        foreach (array_reverse($this->middlewares) as $key => $value) {
            $nextClosure = $this->buildClosure($value, $lastNext, $this->middlewaresVariables[$key]);
            $lastNext = $nextClosure;
        }

        $response = $nextClosure($request);
        return $response;
    }
}