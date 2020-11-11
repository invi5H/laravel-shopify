<?php

namespace Invi5h\ShopifyHelper\Support\Rest;

use Illuminate\Http\Client\Response as OriginalResponse;
use Illuminate\Support\Str;
use Psr\Http\Message\MessageInterface;

class Response extends OriginalResponse
{
    protected PendingRequest $request;
    protected ?Response $nextPage = null;
    protected ?Response $previousPage = null;
    protected ?array $paginationLinks = null;

    /**
     * Create a new response instance.
     *
     * @param  MessageInterface  $response
     * @param  PendingRequest  $request
     */
    public function __construct($response, PendingRequest $request)
    {
        parent::__construct($response);
        $this->request = clone $request;
        $this->request->baseUrl('');
    }

    /**
     * Check if this response has any pagination
     *
     * @return bool
     */
    public function hasPagination() : bool
    {
        return $this->hasNextPage() || $this->hasPreviousPage();
    }

    /**
     * Determine if this response has a next page
     *
     * @return bool
     */
    public function hasNextPage() : bool
    {
        return (bool) (($this->nextPage instanceof static) || $this->getPaginationLinks()['next']);
    }

    /**
     * Parse the pagination links from response headers and if found return them
     *
     * @return array
     */
    protected function getPaginationLinks() : array
    {
        if (!is_null($this->paginationLinks)) {
            return $this->paginationLinks;
        }

        $link = $this->header('Link');
        $links = explode(',', $link);
        $result = [];
        foreach ($links as $link) {
            $url = Str::between(Str::before($link, ';'), '<', '>');
            $type = Str::of(Str::after($link, ';'))->replace('rel=', '')->trim()->trim('"\'')->__toString();
            $result[$type] = $url;
        }

        return $this->paginationLinks = $result;
    }

    /**
     * Determine if this response has a previous page
     *
     * @return bool
     */
    public function hasPreviousPage() : bool
    {
        return (bool) (($this->nextPage instanceof static) || $this->getPaginationLinks()['previous']);
    }

    /**
     * If the next page response is set then return it, otherwise check the shopify headers to get the response from them
     *
     * @return Response|null
     */
    public function getNextPage() : ?Response
    {
        if ($this->nextPage instanceof static) {
            return $this->nextPage;
        }
        if ($this->getPaginationLinks()['next']) {
            $response = $this->request->get($this->getPaginationLinks()['next']);
            $this->nextPage = $response;
            $response->setPreviousPage($this);
            return $response;
        }
        return null;
    }

    /**
     * @param  Response|null  $nextPage
     *
     * @return Response
     */
    public function setNextPage(?Response $nextPage) : Response
    {
        $this->nextPage = $nextPage;
        return $this;
    }

    /**
     * If the previous page response is set then return it, otherwise check the shopify headers to get the response from them
     *
     * @return Response
     */
    public function getPreviousPage() : ?Response
    {
        if ($this->previousPage instanceof static) {
            return $this->previousPage;
        }
        if ($this->getPaginationLinks()['next']) {
            $response = $this->request->get($this->getPaginationLinks()['previous']);
            $this->previousPage = $response;
            $response->setNextPage($this);
            return $response;
        }
        return null;
    }

    /**
     * @param  Response|null  $previousPage
     *
     * @return Response
     */
    public function setPreviousPage(?Response $previousPage) : Response
    {
        $this->previousPage = $previousPage;
        return $this;
    }

    /**
     * This method removes the references to other pages, so that the PHP garbage collector can claim that memory, otherwise it may accumulate slowly for long running processes
     *
     * @return Response
     */
    public function unsetOtherPages() : Response
    {
        $this->nextPage = null;
        $this->previousPage = null;
        return $this;
    }
}
