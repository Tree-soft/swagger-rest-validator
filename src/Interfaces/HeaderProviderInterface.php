<?php

namespace TreeSoft\SwaggerRestValidator\Interfaces;

/**
 * @author Vladimir Barmotin <barmotinvladimir@gmail.com>
 */
interface HeaderProviderInterface
{
    /**
     * @param string $url
     * @param string $method
     * @param int $expectingStatusCode
     *
     * @return array
     */
    public function getHeaders($url, $method, $expectingStatusCode);
}
