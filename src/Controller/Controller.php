<?php

namespace serz\Framework\Controller;


use serz\Framework\Response\Response;
use Twig_Environment;
use Twig_Loader_Filesystem;

class Controller
{
    /**
     * @var Twig_Environment
     */
    public $twig;

    /**
     * Controller constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $loaderTwig = new Twig_Loader_Filesystem($path);
        $this->twig = new Twig_Environment($loaderTwig);
    }

    /**
     * render view with params
     * @param string $name
     * @param array $params
     */
    public function render(string $name, array $params)
    {
        $result = new Response($this->twig->render($name, $params));
        $result->send();
    }
}