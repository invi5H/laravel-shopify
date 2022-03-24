<?php

namespace Invi5h\LaravelShopify\Models;

// phpcs:disable
enum BillingType
{
    case NONE;
    case ONETIME;
    case RECURRING;

    public function enabled() : bool
    {
        return self::NONE !== $this;
    }

    public function disabled() : bool
    {
        return self::NONE === $this;
    }

    public function isOnetime() : bool
    {
        return self::ONETIME === $this;
    }

    public function isRecurring() : bool
    {
        return self::RECURRING === $this;
    }
}
