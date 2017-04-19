<?php

namespace TreeSoft\SwaggerRestValidator\Entities;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class ClientResponse
{
    /**
     * @var int
     */
    private $code;

    /**
     * @var string
     */
    private $body;

    /**
     * ClientResponse constructor.
     *
     * @param int $code
     * @param string $body
     */
    public function __construct($code, $body)
    {
        $this->body = $body;
        $this->code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
}
