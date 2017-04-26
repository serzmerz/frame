<?php

namespace Serz\Framework\Response;


class RedirectResponse extends Response
{

    /**
     * RedirectResponse constructor.
     * @param string $uri
     * @param int $code
     */
    public function __construct(string $uri, int $code = 301)
    {
        $this->code = $code;
        $this->setHeader('Location', $uri);
    }

}