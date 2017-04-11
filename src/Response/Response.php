<?php


namespace Serz\Framework\Response;


class Response
{

    public $code = 200;

    const CODE_STATUS = [
        '200' => 'Ok',
        '201' => 'Created',
        '202' => 'Accepted',
        '204' => 'No Content',
        '300' => 'Multiple Choice',
        '301' => 'Moved Permanently',
        '302' => 'Found',
        '400' => 'Bad Request',
        '401' => 'Unauthorized',
        '402' => 'Payment Required',
        '403' => 'Forbidden',
        '404' => 'Not Found',
        '500' => 'Internal Server Error'
    ];

    protected $headers = [];

    protected $payload;

    /**
     * Response constructor.
     * @param int $code
     * @param string $content
     */
    public function __construct(string $content, int $code = 200)
    {
        $this->code = $code;
        $this->setHeader('Content-Type', 'text/html');
        $this->setPayload($content);
    }

    /**
     * @param $key
     * @param $value
     * @internal param array $headers
     */
    public function setHeader(string $key, string $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * @param $content
     * @internal param mixed $payload
     */
    public function setPayload(string $content)
    {
        $this->payload = $content;
    }

    /**
     *send Response
     */
    public function send()
    {
        $this->sendHeaders();
        $this->sendPayload();
    }

    /**
     *send headers Response
     */
    private function sendHeaders()
    {
        header($_SERVER['SERVER_PROTOCOL'] . " " . $this->code . " " . self::CODE_STATUS[$this->code]);
        if (empty($this->headers)) {
            foreach ($this->headers as $key => $value) {
                header($key . ": " . $value);
            }
        }
    }

    /**
     * send body Response
     */
    private function sendPayload()
    {
        echo $this->payload;
    }

}