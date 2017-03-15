<?php

namespace serz\Framework\Response;

class JsonResponse extends Response
{

    /**
     * JsonResponse constructor.
     * @param string $content
     * @param int $code
     */
    public function __construct(string $content, int $code = 200)
    {
        parent::__construct($content, $code);
        $this->setHeader('Content-Type', 'application/json');
    }

    /**
     * @inheritdoc
     */
    public function sendPayload()
    {
        echo json_encode($this->payload);
    }
}