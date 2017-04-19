<?php

namespace TreeSoft\SwaggerRestValidator\Service;

use Epfremme\Swagger\Parser\SwaggerParser;
use Symfony\Component\Yaml\Yaml;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
class SwaggerDataParser extends SwaggerParser
{

    /**
     * SwaggerDataParser constructor.
     *
     * @param $data
     */
    public function __construct($data)
    {
        $this->data = $this->parse($data);
    }

    /**
     * @param string $swaggerData
     *
     * @return mixed
     */
    protected function parse($swaggerData)
    {
        $data = json_decode($swaggerData, true) ?: Yaml::parse($swaggerData);

        return $data;
    }
}
