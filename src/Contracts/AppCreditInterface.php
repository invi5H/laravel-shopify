<?php

namespace Invi5h\LaravelShopify\Contracts;

interface AppCreditInterface extends AppUsageRecordInterface
{
    public function isTestMode() : bool;

    public function setTestMode(bool $testMode = true) : self;

    public function inTestMode(bool $testMode = true) : self;
}
