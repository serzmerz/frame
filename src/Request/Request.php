<?php


namespace Serz\Framework\Request;

use Serz\Framework\Request\Exceptions\InvalidQueryKeyException;


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
     * @var array collection of HTTP headers
     */
    private $headers;

    /**
     * @var body raw request
     */
    private $rawBody;

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

    /**
     * Returns the header collection.
     * The header collection contains incoming HTTP headers.
     * @return array HeaderCollection the header collection
     */
    public function getHeaders(): array
    {
        if ($this->headers === null) {
            foreach ($_SERVER as $name => $value) {
                if (strncmp($name, 'HTTP_', 5) === 0) {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))));
                    $this->headers[$name] = $value;
                }
            }

            return $this->headers;
        }

        return $this->headers;
    }

    /**
     * Returns the raw HTTP request body.
     * @return string the request body
     */
    public function getRawBody(): string
    {
        if ($this->rawBody === null) {
            $this->rawBody = file_get_contents('php://input');
        }

        return $this->rawBody;
    }

}