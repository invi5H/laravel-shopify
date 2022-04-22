<?php

namespace Invi5h\LaravelShopify\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Contracts\Api\GraphqlClientInterface;
use Invi5h\LaravelShopify\Contracts\Api\ResponseInterface;

class Graphql extends AbstractClient implements GraphqlClientInterface
{
    public function send(string $query, array $variables = [], array $headers = []) : ResponseInterface
    {
        $client = $this->newClient()->withHeaders($headers);

        $data = [];
        $data['query'] = $this->formatGraphqlQuery($query);

        if (!empty($variables)) {
            $data['variables'] = $variables;
        }

        return new Response($client->post('graphql.json', $data), $client);
    }

    protected function newClient(array $options = []) : PendingRequest
    {
        $url = "https://{$this->url}/admin/api/".config('laravelshopify.api_version');

        return $this->makeHttpClient($options)->baseUrl($url)->withHeaders(['X-Shopify-Access-Token' => $this->accessToken]);
    }

    protected function formatGraphqlQuery(string $query) : string
    {
        // remove multiple consecutive whitespaces and whitespaces around the brackets and at the ends
        $query = Str::of($query)->replaceMatches('/\s+/', ' ');
        $query = $query->replaceMatches('/\s{/', '{')->replaceMatches('/{\s/', '{');
        $query = $query->replaceMatches('/\s}/', '}')->replaceMatches('/}\s/', '}');

        return $query->trim()->toString();
    }
}
