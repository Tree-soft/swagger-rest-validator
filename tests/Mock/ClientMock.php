<?php

namespace TreeSoft\SwaggerRestValidator\Tests\Mock;

use TreeSoft\SwaggerRestValidator\Entities\ClientRequest;
use TreeSoft\SwaggerRestValidator\Entities\ClientResponse;
use TreeSoft\SwaggerRestValidator\Interfaces\ClientInterface;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class ClientMock implements ClientInterface
{
    /**
     * @var bool
     */
    private $errorMode;

    function __construct($errorMode = false)
    {
        $this->errorMode = $errorMode;
    }

    /**
     * @param ClientRequest $request
     *
     * @return ClientResponse
     */
    public function send(ClientRequest $request)
    {
        return $this->errorMode?$this->sendWithError($request):$this->sendNormal($request);
    }

    /**
     * @param ClientRequest $request
     *
     * @return ClientResponse
     */
    private function sendWithError(ClientRequest $request)
    {
        switch($request->getUrl()) {
            case 'test/users/1?requireVar=1':
                return new ClientResponse(200, '{"id":1,"first_name":"Vovan"}');
            case 'test/users/1':
                return new ClientResponse(42, '{"message":"error"}');
            case 'test/users/2?requireVar=1':
                return new ClientResponse(404, '{"message":"page not found"}');
            case 'test/group_leader':
                return new ClientResponse(200, '{"id":1,"name":"Vovan","groupName":"friends"}');

        }

        return new ClientResponse(500, '{"message":"internal server error"}');
    }

    /**
     * @param ClientRequest $request
     *
     * @return ClientResponse
     */
    private function sendNormal(ClientRequest $request)
    {
        switch($request->getUrl()) {
            case 'test/users/1?requireVar=1':
                return new ClientResponse(200, '{"id":1,"name":"Vovan"}');
            case 'test/users/1':
                return new ClientResponse(400, '{"message":"error"}');
            case 'test/users/2?requireVar=1':
                return new ClientResponse(404, '{"message":"page not found"}');
            case 'test/group_leader':
                return new ClientResponse(200, '{"id":1,"name":"Vovan","groupName":"friends"}');
        }

        return new ClientResponse(500, '{"message":"internal server error"}');
    }
}
