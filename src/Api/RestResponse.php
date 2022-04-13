<?php

namespace Invi5h\LaravelShopify\Api;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response as LaravelResponse;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Invi5h\LaravelShopify\Contracts\Api\RestResponseInterface;
use WeakReference;

class RestResponse extends Response implements RestResponseInterface
{
    protected ?WeakReference $nextPage = null;

    protected ?WeakReference $previousPage = null;

    protected ?array $paginationLinks = null;

    public function __construct(LaravelResponse $response, PendingRequest $request)
    {
        parent::__construct($response);
        $this->request = clone $request;
        $this->request->baseUrl('');
    }

    public function hasPagination() : bool
    {
        return $this->hasNextPage() || $this->hasPreviousPage();
    }

    public function hasNextPage() : bool
    {
        return (optional($this->nextPage)->get() instanceof static) || $this->getPaginationLinks()['next'];
    }

    protected function getPaginationLinks() : array
    {
        if (null !== $this->paginationLinks) {
            return $this->paginationLinks;
        }

        $link = $this->response->header('Link');
        $links = explode(',', $link);
        $result = [];
        foreach ($links as $link) {
            $url = Str::between(Str::before($link, ';'), '<', '>');
            $type = Str::of(Str::after($link, ';'))->replace('rel=', '')->trim()->trim('"\'')->__toString();
            $result[$type] = $url;
        }

        return $this->paginationLinks = $result;
    }

    public function hasPreviousPage() : bool
    {
        return (optional($this->previousPage)->get() instanceof static) || $this->getPaginationLinks()['previous'];
    }

    public function getNextPage() : ?static
    {
        $next = optional($this->nextPage)->get();
        if ($next instanceof static) {
            return $next;
        }
        if (Arr::exists($this->getPaginationLinks(), 'next')) {
            $response = new self($this->request->get($this->getPaginationLinks()['next']), $this->request);
            $this->setNextPage($response);
            $response->setPreviousPage($this);

            return $response;
        }

        return null;
    }

    public function setNextPage(self $nextPage) : static
    {
        $this->nextPage = WeakReference::create($nextPage);

        return $this;
    }

    public function getPreviousPage() : ?static
    {
        $previous = optional($this->previousPage)->get();
        if ($previous instanceof static) {
            return $previous;
        }
        if (Arr::exists($this->getPaginationLinks(), 'previous')) {
            $response = new self($this->request->get($this->getPaginationLinks()['previous']), $this->request);
            $this->setPreviousPage($response);
            $response->setNextPage($this);

            return $response;
        }

        return null;
    }

    public function setPreviousPage(self $previousPage) : static
    {
        $this->previousPage = WeakReference::create($previousPage);

        return $this;
    }
}
