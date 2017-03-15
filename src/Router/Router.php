<?php

namespace serz\Framework\Router;

use serz\Framework\Request\Request;
use serz\Framework\Router\Exceptions\{
    InvalidRouteArgumentException,
    InvalidRouteNameException,
    RouteNotFoundException
};


/**
 * Class Router
 * Parse config and create route
 * @package serz\Framework\Router
 */
class Router
{

    /**
     * config params
     * @var array
     */
    public $routes = [];

    /**
     * Router constructor.
     * @param array $config
     */
    public function __construct(array $config)
    {
        foreach ($config as $key => $value) {

            $existed_params = $this->getParams($value);
            $this->routes[$key] = [
                "pattern" => $value["pattern"],
                "method" => isset($value["method"]) ? $value["method"] : "GET",
                "controller_name" => $this->getRouteController($value),
                "controller_action" => $this->getRouteMethod($value),
                "regexp" => $this->getRegExp($value, $existed_params),
                "params" => $existed_params
            ];

        }
        debug($this->routes);
    }

    /**
     * Create route with variables
     * @param Request $request
     * @return Route
     * @throws RouteNotFoundException
     */
    public function getRoute(Request $request): Route
    {
        $uri = $request->getUri();

        foreach ($this->routes as $key => $value) {

            if ($value['method'] == $request->getMethod()) {
                if (preg_match_all($value['regexp'], $uri, $matches)) {

                    $result = new Route($key, $value['controller_name'], $value['controller_action']);

                    if (!empty($value['params'])) {

                        $variables = array_pop($matches);
                        $variables_array = [];
                        for ($i = 0; $i < count($value['params']); $i++) {
                            $variables_array[$value['params'][$i]] = $variables[$i];
                        }
                        $result->setVariables($variables_array);
                    }

                    return $result;
                }
            }
        }
        throw new RouteNotFoundException("Route not found!");
    }


    /**
     * return controller name
     * @param array $value
     * @return string
     */
    private function getRouteController(array $value): string
    {
        $result = explode("@", $value["action"]);
        return array_shift($result);
    }

    /**
     * return controller method
     * @param array $value
     * @return string
     */
    private function getRouteMethod(array $value): string
    {
        $result = explode("@", $value["action"]);
        return array_pop($result);
    }

    /**
     * return array variables in pattern
     * @param array $value
     * @return array
     */
    private function getParams(array $value): array
    {
        preg_match_all("/{.+}/U", $value["pattern"], $result);
        $result = $result[0];
        foreach ($result as $key => $item) {
            $result[$key] = substr($item, 1, -1);
        }
        return $result;
    }

    /**
     * build regexp on pattern
     * @param array $value
     * @param array $existed_params
     * @return string
     */
    private function getRegExp(array $value, array $existed_params): string
    {
        $result = str_replace("/", "\/", $value["pattern"]);
        $result = "/^" . $result . "$/";

        foreach ($existed_params as $key => $item) {
            if (array_key_exists($item, $value["variables"])) {
                $variable = $value["variables"][$item];
            } else {
                $variable = "[^\/]+";
            }
            $result = str_replace("{" . $item . "}", "(" . $variable . ")", $result);
        }

        return $result;
    }

    /**
     * Build route link with params
     * @param string $name_route
     * @param array $params
     * @return string
     * @throws InvalidRouteArgumentException
     * @throws InvalidRouteNameException
     */
    public function getLink(string $name_route, array $params): string
    {

        if (array_key_exists($name_route, $this->routes))
            $result = $this->routes[$name_route]["pattern"];
        else throw new InvalidRouteNameException("Route \"$name_route\" not found!");

        foreach ($this->routes[$name_route]["params"] as $key => $value) {

            if (array_key_exists($value, $params))
                $result = str_replace("{" . $value . "}", $params[$value], $result);
            else throw new InvalidRouteArgumentException("Argument \"$value\" not found in params array.");

        }

        return $result;
    }
}