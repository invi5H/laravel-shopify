<?php

namespace Invi5h\LaravelShopify\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response as BaseResponse;
use Illuminate\Support\Traits\ForwardsCalls;
use Invi5h\LaravelShopify\Contracts\Api\ResponseInterface;

class Response implements ResponseInterface
{
    use ForwardsCalls;

    public function __construct(protected BaseResponse $response, protected ?PendingRequest $request = null)
    {
    }

    public function __call(string $name, array $arguments)
    {
        return $this->forwardCallTo($this->response, $name, $arguments);
    }
}
