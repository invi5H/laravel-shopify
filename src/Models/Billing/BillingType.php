<?php

namespace Invi5h\LaravelShopify\Models\Billing;

// phpcs:disable
enum BillingType: string
{
    case NONE = 'none';
    case ONETIME = 'onetime';
    case RECURRING = 'recurring';

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
