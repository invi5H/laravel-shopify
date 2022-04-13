<?php

namespace Invi5h\LaravelShopify\Api;

use Illuminate\Http\Client\PendingRequest;
use Invi5h\LaravelShopify\Contracts\Api\RestClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\RestResponseInterface;

class Rest extends AbstractClient implements RestClientInterface
{
    public function get(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        $client = $this->newClient()->withHeaders($headers);

        return new RestResponse($client->get("{$resource}.json", $params), $client);
    }

    public function post(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        $client = $this->newClient()->withHeaders($headers);

        return new RestResponse($client->post("{$resource}.json", $params), $client);
    }

    public function put(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        $client = $this->newClient()->withHeaders($headers);

        return new RestResponse($client->put("{$resource}.json", $params), $client);
    }

    public function delete(string $resource, array $params = [], array $headers = []) : RestResponseInterface
    {
        $client = $this->newClient()->withHeaders($headers);

        return new RestResponse($client->delete("{$resource}.json", $params), $client);
    }

    protected function newClient(array $options = []) : PendingRequest
    {
        $url = "https://{$this->url}/admin/api/".config('laravelshopify.api_version');

        return $this->makeHttpClient($options)->baseUrl($url)->withHeaders(['X-Shopify-Access-Token' => $this->accessToken]);
    }
}
