<?php

namespace TreeSoft\SwaggerRestValidator\Interfaces;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
interface DataProviderInterface
{
    /**
     * @param string $url
     * @param string $method
     * @param int $expectingStatusCode
     *
     * @return array
     */
    public function getData($url, $method, $expectingStatusCode);
}
