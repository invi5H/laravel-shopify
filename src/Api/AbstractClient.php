<?php

namespace Invi5h\LaravelShopify\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

abstract class AbstractClient
{
    public function __construct(protected string $url, protected string $accessToken)
    {
    }

    abstract protected function newClient(array $options = []) : PendingRequest;

    protected function makeHttpClient(array $options = []) : PendingRequest
    {
        return Http::asJson()->acceptJson()->withoutRedirecting()->withOptions($options);
    }
}
