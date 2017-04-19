<?php

namespace TreeSoft\SwaggerRestValidator\Entities;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class Error
{
    /**
     * @var string
     */
    private $message;
    /**
     * @var int
     */
    private $expectingStatusCode;
    /**
     * @var ClientRequest
     */
    private $request;

    /**
     * @var ClientResponse
     */
    private $response;

    /**
     * Error constructor.
     *
     * @param $message
     * @param $expectingStatusCode
     * @param ClientRequest $request
     * @param ClientResponse $response
     */
    function __construct($message, $expectingStatusCode, ClientRequest $request, ClientResponse $response)
    {
        $this->message = $message;
        $this->expectingStatusCode = $expectingStatusCode;
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return ClientRequest
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * @return ClientResponse
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @return int
     */
    public function getExpectingStatusCode()
    {
        return $this->expectingStatusCode;
    }
}
