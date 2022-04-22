<?php

namespace Invi5h\LaravelShopify\Contracts\Api;

interface RestResponseInterface extends ResponseInterface
{
    public function hasPagination() : bool;

    public function hasNextPage() : bool;

    public function hasPreviousPage() : bool;

    /**
     * Get the response for the next page.
     */
    public function getNextPage() : ?static;

    /**
     * Get the response for the previous page.
     */
    public function getPreviousPage() : ?static;
}
