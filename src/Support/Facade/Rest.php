<?php

namespace Invi5h\ShopifyHelper\Support\Facade;

use Illuminate\Http\Client\ResponseSequence;
use Illuminate\Support\Facades\Http;
use Invi5h\ShopifyHelper\Support\Rest\Factory;
use Invi5h\ShopifyHelper\Support\Rest\PendingRequest;
use Invi5h\ShopifyHelper\Support\Rest\Response;

/**
 * @method static PendingRequest baseUrl(string $url)
 * @method static PendingRequest asJson()
 * @method static PendingRequest asForm()
 * @method static PendingRequest attach(string $name, string $contents, string|null $filename = null, array $headers = [])
 * @method static PendingRequest asMultipart()
 * @method static PendingRequest bodyFormat(string $format)
 * @method static PendingRequest contentType(string $contentType)
 * @method static PendingRequest acceptJson()
 * @method static PendingRequest accept(string $contentType)
 * @method static PendingRequest retry(int $times, int $sleep = 0)
 * @method static PendingRequest withHeaders(array $headers)
 * @method static PendingRequest withBasicAuth(string $username, string $password)
 * @method static PendingRequest withDigestAuth(string $username, string $password)
 * @method static PendingRequest withToken(string $token, string $type = 'Bearer')
 * @method static PendingRequest withCookies(array $cookies, string $domain)
 * @method static PendingRequest withoutRedirecting()
 * @method static PendingRequest withoutVerifying()
 * @method static PendingRequest timeout(int $seconds)
 * @method static PendingRequest withOptions(array $options)
 * @method static PendingRequest beforeSending(callable $callback)
 * @method static Response get(string $url, array $query = [])
 * @method static Response post(string $url, array $data = [])
 * @method static Response patch(string $url, array $data = [])
 * @method static Response put(string $url, array $data = [])
 * @method static Response delete(string $url, array $data = [])
 * @method static Response send(string $method, string $url, array $options = [])
 * @method static PendingRequest stub(callable $callback)
 * @method static ResponseSequence fakeSequence(string $urlPattern = '*')
 * @see Factory
 */
class Rest extends Http
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return Factory::class;
    }
}
