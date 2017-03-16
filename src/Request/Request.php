<?php


namespace serz\Framework\Request;

use serz\Framework\Request\Exceptions\InvalidQueryKeyException;


/**
 * Class Request
 * @package serz\Framework\Request
 */
class Request
{

    /**
     * request instance
     * @var null
     */
    private static $request = null;

    /**
     * Request constructor.
     */
    private function __construct()
    {
    }

    /**
     * @return Request
     */
    public static function getRequest(): self
    {
        if (!self::$request) {
            self::$request = new self();
        }

        return self::$request;
    }

    /**
     * Return Request URI
     * @return string
     */
    public function getUri(): string
    {

        $result = explode("?", $_SERVER["REQUEST_URI"]);

        return array_shift($result);

    }

    /**
     * Return Request Method
     * @return string
     */
    public function getMethod(): string
    {
        return $_SERVER["REQUEST_METHOD"];
    }

    /**
     * Return array query params
     * @return array
     */
    public function getQueryString(): array
    {

        $buffer = explode('?', $_SERVER["REQUEST_URI"]);

        $variable = explode('&', array_pop($buffer));

        $array_variable = [];

        foreach ($variable as $value) {

            $items = explode('=', $value);
            $array_variable[array_shift($items)] = array_pop($items);
        }

        return $array_variable;
    }

    /**
     * get param on key
     * @param string $key
     * @return string
     * @throws InvalidQueryKeyException
     */
    public function getParamQuery(string $key): string
    {
        $result = $this->getQueryString();
        if (array_key_exists($key, $result))
            return $result[$key];
        else throw new InvalidQueryKeyException("This params is not valid!");
    }

}