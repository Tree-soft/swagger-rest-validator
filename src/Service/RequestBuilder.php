<?php

namespace TreeSoft\SwaggerRestValidator\Service;

use TreeSoft\SwaggerRestValidator\Entities\ClientRequest;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class RequestBuilder
{
    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $method;

    /**
     * @var array
     */
    protected $body;

    /**
     * @var array
     */
    protected $headers;

    /**
     * @var array
     */
    protected $pathVariables;

    /**
     * @var array
     */
    protected $queryVariables;

    public function __construct($url, $method = 'GET')
    {
        $this->url = $url;
        $this->method = $method;

        $this->body = [];
        $this->headers = [];
        $this->pathVariables = [];
        $this->queryVariables = [];
    }

    /**
     * @param string $method
     */
    public function setMethod($method)
    {
        $this->method = $method;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setQueryVariable($key, $value)
    {
        $this->queryVariables[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setPathVariable($key, $value)
    {
        $this->pathVariables[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    /**
     * @param string $key
     * @param mixed $value
     */
    public function setBody($key, $value)
    {
        $this->body[$key] = $value;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers) {
        $this->headers = $headers;
    }

    /**
     * @return ClientRequest
     */
    public function build()
    {
        $url = $this->url;

        foreach($this->pathVariables as $key => $value) {
            $url = str_replace('{'.$key.'}', $value, $url);
        }

        $queryString = http_build_query($this->queryVariables);
        if($queryString) {
            $url .= '?'.$queryString;
        }

        return new ClientRequest($url, $this->method, $this->body, $this->headers);
    }
}
