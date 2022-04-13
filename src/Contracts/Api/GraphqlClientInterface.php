<?php

namespace Invi5h\LaravelShopify\Contracts\Api;

interface GraphqlClientInterface
{
    public function send(string $query, array $variables = [], array $headers = []) : ResponseInterface;
}
