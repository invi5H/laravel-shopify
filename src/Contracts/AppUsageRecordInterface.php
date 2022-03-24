<?php

namespace Invi5h\LaravelShopify\Contracts;

interface AppUsageRecordInterface
{
    public function getName() : string;

    public function setName(string $name) : self;

    public function getAmount() : float;

    public function setAmount(float $amount) : self;

    public function getCurrency() : string;

    public function setCurrency(string $currency) : self;
}
