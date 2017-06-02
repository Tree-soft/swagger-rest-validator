<?php

namespace TreeSoft\SwaggerRestValidator;

use Doctrine\Common\Collections\ArrayCollection;
use Epfremme\Swagger\Entity\Response;
use Epfremme\Swagger\Entity\Schemas\RefSchema;
use Epfremme\Swagger\Entity\Schemas\SchemaInterface;
use Epfremme\Swagger\Entity\Swagger;
use JsonSchema\Validator;
use TreeSoft\SwaggerRestValidator\Entities\ClientRequest;
use TreeSoft\SwaggerRestValidator\Entities\ClientResponse;
use TreeSoft\SwaggerRestValidator\Entities\Error;
use TreeSoft\SwaggerRestValidator\Interfaces\ClientInterface;
use TreeSoft\SwaggerRestValidator\Interfaces\DataProviderInterface;
use TreeSoft\SwaggerRestValidator\Interfaces\HeaderProviderInterface;
use TreeSoft\SwaggerRestValidator\Service\RequestBuilder;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class SwaggerValidator
{
    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Swagger
     */
    protected $swagger;

    /**
     * @var array
     */
    protected $swaggerDocArray;

    /**
     * @var string
     */
    private $baseURL;

    /**
     * @var DataProviderInterface
     */
    protected $dataProvider;

    /**
     * @var HeaderProviderInterface
     */
    protected $headerProvider;

    /**
     * @var ArrayCollection
     */
    private $errors;

    /**
     * SwaggerValidator constructor.
     *
     * @param ClientInterface $client
     * @param DataProviderInterface $dataProvider
     * @param Swagger $swagger
     * @param array $swaggerDocArray
     * @param string $host
     * @param HeaderProviderInterface|null $headerProvider
     * @internal param string $baseURL
     * @internal param string $jsonPath
     */
    public function __construct(
        ClientInterface $client,
        DataProviderInterface $dataProvider,
        Swagger $swagger,
        array $swaggerDocArray,
        $host = '127.0.0.1',
        HeaderProviderInterface $headerProvider = null
    )
    {
        $this->client = $client;
        $this->baseURL = $host.$swagger->getBasePath();
        $this->dataProvider = $dataProvider;
        $this->headerProvider = $headerProvider;

        $this->errors = new ArrayCollection();

        $this->swagger = $swagger;
        $this->swaggerDocArray = $swaggerDocArray;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        foreach ($this->swagger->getPaths() as $path => $pathEntity) {
            foreach($pathEntity->getOperations() as $method => $operation) {
                /** @var Response $response */
                foreach($operation->getResponses() as $code => $response) {
                    $this->validateOperation($path, $method, $code, $operation->getParameters(), $response->getSchema());
                }
            }
        }

        return $this->errors->isEmpty();
    }

    /**
     * @return ArrayCollection
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $path
     * @param string $method
     * @param int $expectingCode
     * @param SchemaInterface $responseSchema
     *
     * @return array
     */
    protected function getResponseValidationSchema($path, $method, $expectingCode, SchemaInterface $responseSchema)
    {
        if($responseSchema->getType() == RefSchema::REF_TYPE) {
            $schema = $this->swaggerDocArray['definitions'][$this->getDefinitionName($responseSchema)];
        } else {
            $schema = $this->swaggerDocArray['paths'][$path][$method]['responses'][$expectingCode]['schema'];
        }

        $schema['definitions'] = $this->swaggerDocArray['definitions'];

        return $schema;
    }

    /**
     * @param string $path
     * @param string $method
     * @param int $code
     * @param ArrayCollection $operationParameters
     * @param array $operationData
     *
     * @return ClientRequest
     */
    protected function buildRequest($path, $method, $code, $operationParameters, $operationData)
    {
        $requestBuilder = new RequestBuilder($this->baseURL.$path, $method);

        if($this->headerProvider) {
            $requestBuilder->setHeaders($this->headerProvider->getHeaders($path, $method, $code));
        }

        if(!is_null($operationParameters)) {

            foreach ($operationData as $key => $value) {
                $parameter = $operationParameters->filter(function($item) use ($key){
                    return $item->getName() == $key;
                })->first();

                if(!$parameter) {
                    continue;
                }

                switch ($parameter->getIn()) {
                    case 'path':
                        $requestBuilder->setPathVariable($key, $value);
                        break;
                    case 'query':
                        $requestBuilder->setQueryVariable($key, $value);
                        break;
                    case 'body':
                        $requestBuilder->setBody($key, $value);
                        break;
                }
            }
        }

        return $requestBuilder->build();
    }

    /**
     * @param ClientRequest $request
     * @param $expectingCode
     *
     * @return ClientResponse
     */
    protected function sendRequest(ClientRequest $request, $expectingCode)
    {
        $clientResponse = $this->client->send($request);

        if($clientResponse->getCode() != $expectingCode) {
            $this->errors->add(new Error(
                sprintf('Expected %s code, got %s', $expectingCode, $clientResponse->getCode()),
                $expectingCode,
                $request,
                $clientResponse
            ));
        }

        return $clientResponse;
    }

    /**
     * @param RefSchema $refSchema
     *
     * @return string
     */
    protected function getDefinitionName(RefSchema $refSchema)
    {
        return explode('/', $refSchema->getRef())[2];
    }

    /**
     * @param $path
     * @param $method
     * @param $code
     * @param $parameters
     * @param SchemaInterface $responseSchema
     */
    protected function validateOperation($path, $method, $code, $parameters, SchemaInterface $responseSchema = null)
    {
        $operationData = $this->dataProvider->getData($path, $method, $code);

        $clientRequest = $this->buildRequest($path, $method, $code, $parameters, $operationData);
        $clientResponse = $this->sendRequest($clientRequest, $code);

        $data = json_decode($clientResponse->getBody());

        if (!is_null($responseSchema)) {
            $validator = new Validator();
            $validator->validate($data, $this->getResponseValidationSchema($path, $method, $code, $responseSchema));

            foreach ($validator->getErrors() as $error) {
                $this->getErrors()->add(new Error(($error['property'] ? $error['property'] . ' - ' : '') . $error['message'], $code, $clientRequest, $clientResponse));
            }
        }
    }
}
