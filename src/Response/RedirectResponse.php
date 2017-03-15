<?php

namespace serz\Framework\Response;


class RedirectResponse extends Response
{

    /**
     * RedirectResponse constructor.
     * @param string $uri
     * @param int $code
     */
    public function __construct(string $uri, int $code = 200)
    {
        $this->code = $code;
        $this->setHeader('Location', $uri);
    }

    /**
     * override method send
     */
    public function send()
    {
        // to do nothing.
    }
}