<?php

namespace Invi5h\LaravelShopify\Models;

// phpcs:disable
enum AppType
{
    case PUBLIC;
    case CUSTOM;

    public function isPublic() : bool
    {
        return self::PUBLIC === $this;
    }

    public function isCustom() : bool
    {
        return self::CUSTOM === $this;
    }
}
