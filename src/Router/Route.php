<?php

namespace serz\Framework\Router;


class Route
{

    private $name;

    private $controllerName;

    private $controllerAction;

    private $variables;

    /**
     * Route constructor.
     * @param $name
     * @param $controllerName
     * @param $controllerAction
     */
    public function __construct(string $name, string $controllerName, string $controllerAction)
    {
        $this->name = $name;
        $this->controllerName = $controllerName;
        $this->controllerAction = $controllerAction;
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