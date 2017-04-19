<?php

namespace TreeSoft\SwaggerRestValidator\Interfaces;

use TreeSoft\SwaggerRestValidator\Entities\ClientRequest;
use TreeSoft\SwaggerRestValidator\Entities\ClientResponse;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
interface ClientInterface
{
    /**
     * @param ClientRequest $request
     *
     * @return ClientResponse
     */
    public function send(ClientRequest $request);
}
