<?php

namespace Invi5h\LaravelShopify\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Invi5h\LaravelShopify\Contracts\ShopModelInterface;

class ShopifyInstallEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public ShopModelInterface $shop)
    {
    }
}
