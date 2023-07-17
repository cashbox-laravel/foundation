<?php

declare(strict_types=1);

namespace Tests\Fixtures\Payments;

use Cashbox\Core\Enums\CurrencyEnum;
use Cashbox\Tinkoff\Credit\Resources\TinkoffCreditResource;

class Tinkoff extends TinkoffCreditResource
{
    public function currency(): CurrencyEnum
    {
        return CurrencyEnum::USD;
    }

    public function sum(): int
    {
        return $this->payment->price;
    }
}
