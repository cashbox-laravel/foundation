<?php

declare(strict_types=1);

namespace Tests\Fixtures\Payments;

use Cashbox\Cash\Resources\CashResource;
use Cashbox\Core\Enums\CurrencyEnum;

class Tinkoff extends CashResource
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
