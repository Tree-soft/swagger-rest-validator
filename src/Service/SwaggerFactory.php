<?php

namespace TreeSoft\SwaggerRestValidator\Service;

use Epfremme\Swagger\Entity\Swagger;
use JMS\Serializer\DeserializationContext;
use Epfremme\Swagger\Factory\SwaggerFactory as BaseSwaggerFactory;
use JMS\Serializer\EventDispatcher\EventDispatcher;
use JMS\Serializer\SerializerBuilder;
use TreeSoft\SwaggerRestValidator\Listeners\SerializationSubscriber;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class SwaggerFactory extends BaseSwaggerFactory
{

    public function __construct()
    {
        $serializerBuilder = new SerializerBuilder();

        $serializerBuilder->configureListeners(function(EventDispatcher $eventDispatcher) {
            $eventDispatcher->addSubscriber(new SerializationSubscriber());
        });

        $this->serializer = $serializerBuilder->build();
    }

    /**
     * @param $jsonString
     *
     * @return Swagger
     */
    public function buildFromJsonString($jsonString)
    {
        $swagger = new SwaggerDataParser($jsonString);
        $context = new DeserializationContext();
        $context->setVersion(
            $swagger->getVersion()
        );

        return $this->serializer->deserialize($swagger, Swagger::class, 'json', $context);
    }

    public function build($file)
    {
        return parent::build($file);
    }


}
