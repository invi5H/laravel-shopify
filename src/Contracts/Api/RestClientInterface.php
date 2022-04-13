<?php

namespace Invi5h\LaravelShopify\Contracts\Api;

interface RestClientInterface
{
    public function get(string $resource, array $params = [], array $headers = []) : RestResponseInterface;

    public function post(string $resource, array $params = [], array $headers = []) : RestResponseInterface;

    public function put(string $resource, array $params = [], array $headers = []) : RestResponseInterface;

    public function delete(string $resource, array $params = [], array $headers = []) : RestResponseInterface;
}
