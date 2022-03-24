<?php

namespace Invi5h\LaravelShopify\Models\Billing;

use Invi5h\LaravelShopify\Contracts\AppCreditInterface;
use JetBrains\PhpStorm\Pure;

class Credit extends UsageRecord implements AppCreditInterface
{
    public bool $testMode;

    #[Pure]
    public function isTestMode() : bool
    {
        return $this->getTestMode();
    }

    public function getTestMode() : bool
    {
        return $this->testMode;
    }

    public function setTestMode(bool $testMode = true) : self
    {
        $this->testMode = $testMode;

        return $this;
    }

    public function inTestMode(bool $testMode = true) : self
    {
        return $this->setTestMode($testMode);
    }
}
