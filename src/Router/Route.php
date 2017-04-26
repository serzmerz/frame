<?php

namespace Serz\Framework\Router;


class Route
{

    private $name;

    private $controllerName;

    private $controllerAction;

    private $variables = [];
    
    public $middleware = [];

    /**
     * @return array
     */
    public function getMiddleware(): array
    {
        return $this->middleware;
    }
    /**
     * Route constructor.
     * @param $name
     * @param $controllerName
     * @param $controllerAction
     */
    public function __construct(string $name, string $controllerName, string $controllerAction, array $middleware)
    {
        $this->name = $name;
        $this->controllerName = $controllerName;
        $this->controllerAction = $controllerAction;
        $this->middleware = $middleware;
    }

    /**
     * Set route variables
     * @param array $variables_array
     */
    public function setVariables(array $variables_array)
    {
        $this->variables = $variables_array;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getControllerName(): string
    {
        return $this->controllerName;
    }

    /**
     * @return string
     */
    public function getControllerAction(): string
    {
        return $this->controllerAction;
    }

    /**
     * @return array
     */
    public function getVariables(): array
    {
        return $this->variables;
    }


}