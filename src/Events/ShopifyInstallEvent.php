<?php

namespace Invi5h\LaravelShopify\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;

class ShopifyInstallEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public ShopModelInterface $shop)
    {
    }
}
