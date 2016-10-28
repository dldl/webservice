<?php

namespace dLdL\WebService\Http;

/**
 * Simple request to a WebService.
 */
class Request
{
    private $url;
    private $method;
    private $parameters;

    public function __construct($url, $method = 'GET', $parameters = [])
    {
        $this->url = $url;
        $this->method = $method;
        $this->parameters = $parameters;
    }

    public function getUrl()
    {
        return $this->url;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
}
