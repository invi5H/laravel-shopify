<?php

namespace Invi5h\LaravelShopify\Models;

// phpcs:disable
enum AppType: string
{
    case PUBLIC = 'public';
    case CUSTOM = 'custom';

    public function isPublic() : bool
    {
        return self::PUBLIC === $this;
    }

    public function isCustom() : bool
    {
        return self::CUSTOM === $this;
    }
}
