<?php

namespace TreeSoft\SwaggerRestValidator\Entities;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class ClientRequest
{
    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $body;

    /**
     * @var string
     */
    private $method;

    /**
     * @var array
     */
    private $headers;

    /**
     * ClientRequest constructor.
     *
     * @param $url
     * @param $method
     * @param array $body
     * @param array $headers
     */
    function __construct($url, $method, array $body = [], $headers = [])
    {
        $this->headers = $headers;
        $this->url = $url;
        $this->method = $method;
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return array
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
}
