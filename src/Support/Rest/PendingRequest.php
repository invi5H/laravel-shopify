<?php

namespace Invi5h\ShopifyHelper\Support\Rest;

use Exception;
use GuzzleHttp\Exception\ConnectException;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Factory;
use Illuminate\Http\Client\PendingRequest as OriginalPendingRequest;
use Illuminate\Support\Str;

/**
 * @method static Response get(string $url, array $query = [])
 * @method static Response post(string $url, array $data = [])
 * @method static Response patch(string $url, array $data = [])
 * @method static Response put(string $url, array $data = [])
 * @method static Response delete(string $url, array $data = [])
 */
class PendingRequest extends OriginalPendingRequest
{
    public function __construct(Factory $factory = null)
    {
        parent::__construct($factory);

        $this->acceptJson()->withoutRedirecting();
    }

    /**
     * @param  string  $method
     * @param  string  $url
     * @param  array  $options
     *
     * @return Response
     * @throws Exception
     */
    public function send(string $method, string $url, array $options = [])
    {
        $origUrl = $url;
        $origOptions = $options;
        $url = ltrim(rtrim($this->baseUrl, '/').'/'.ltrim(Str::contains($url, '.json') ? $url : Str::finish($url, '.json'), '/'), '/');

        if (isset($options[$this->bodyFormat])) {
            $options[$this->bodyFormat] = array_merge($options[$this->bodyFormat], $this->pendingFiles);
        }

        $this->pendingFiles = [];

        return retry($this->tries ?? 1, function () use ($method, $url, $options, $origUrl, $origOptions) {
            try {
                $response = tap(new Response($this->buildClient()->request($method, $url, $this->mergeOptions([
                        'laravel_data' => $options[$this->bodyFormat] ?? [],
                        'on_stats' => function ($transferStats) {
                            $this->transferStats = $transferStats;
                        },
                ], $options)), $this), function ($response) {
                    $response->cookies = $this->cookies;
                    $response->transferStats = $this->transferStats;

                    if ($this->tries > 1 && !$response->successful()) {
                        $response->throw();
                    }
                });
                if (429 === $response->status()) {
                    usleep(($this->retryDelay ?? 100) * 1000);
                    return $this->send($method, $origUrl, $origOptions);
                }
                return $response;
            } catch (ConnectException $e) {
                throw new ConnectionException($e->getMessage(), 0, $e);
            }
        }, $this->retryDelay ?? 100);
    }
}
