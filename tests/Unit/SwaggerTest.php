<?php

namespace TreeSoft\SwaggerRestValidator\Tests\Unit;

use PHPUnit\Framework\TestCase;
use TreeSoft\SwaggerRestValidator\Interfaces\DataProviderInterface;
use TreeSoft\SwaggerRestValidator\Service\SwaggerFactory;
use TreeSoft\SwaggerRestValidator\SwaggerValidator;
use TreeSoft\SwaggerRestValidator\Tests\Mock\ClientMock;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class SwaggerTest extends TestCase implements DataProviderInterface
{
    /**
     * @var array
     */
    private $parameters = [
        "/users/{id}" => [
            "get" => [
                200 => [
                    "id" => 1,
                    "requireVar" => 1
                ],
                400 => [
                    "id" => 1
                ],
                404 => [
                    "id" => 2,
                    "requireVar" => 1
                ]
            ]
        ]
    ];

    public function testValidationTrue()
    {
        $swaggerFactory = new SwaggerFactory();
        $json = file_get_contents(__DIR__.'/../test.json');

        $swagger = $swaggerFactory->buildFromJsonString($json);
        $swaggerDocArray = json_decode($swaggerFactory->serialize($swagger),true);
        $swaggerValidator = new SwaggerValidator(new ClientMock(), $this, $swagger, $swaggerDocArray, 'test');

        $this->assertTrue($swaggerValidator->validate());
    }

    public function testValidationFalse()
    {
        $swaggerFactory = new SwaggerFactory();
        $json = file_get_contents(__DIR__.'/../test.json');
        $swagger = $swaggerFactory->buildFromJsonString($json);
        $swaggerDocArray = json_decode($swaggerFactory->serialize($swagger),true);
        $swaggerValidator = new SwaggerValidator(new ClientMock(true), $this, $swagger, $swaggerDocArray, 'test');

        $this->assertFalse($swaggerValidator->validate());

        $this->assertEquals(2, $swaggerValidator->getErrors()->count());
        $this->assertEquals('name - The property name is required', $swaggerValidator->getErrors()->get(0)->getMessage());
        $this->assertEquals('Expected 400 code, got 42', $swaggerValidator->getErrors()->get(1)->getMessage());
    }

    /**
     * @param string $url
     * @param string $method
     * @param int $expectingStatusCode
     *
     * @return array
     */
    public function getData($url, $method, $expectingStatusCode)
    {
        $data = [];
        if(isset($this->parameters[$url][$method][$expectingStatusCode])) {
            $parameters = $this->parameters[$url][$method][$expectingStatusCode];
            $parameters = is_callable($parameters)?$parameters():$parameters;

            $data = $parameters;

        }

        return $data;
    }
}
