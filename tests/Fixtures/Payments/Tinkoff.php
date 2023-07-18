<?php

declare(strict_types=1);

namespace Tests\Fixtures\Payments;

use Cashbox\Core\Enums\CurrencyEnum;
use Cashbox\Tinkoff\Credit\Data\ContactData;
use Cashbox\Tinkoff\Credit\Data\ProductData;
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

    public function contact(): ?ContactData
    {
        return ContactData::from([
            'fio'         => [
                'firstName'  => fake()->firstName,
                'middleName' => fake()->firstName,
                'lastName'   => fake()->lastName,
            ],
            'mobilePhone' => '1234567890',
            'email'       => fake()->safeEmail,
        ]);
    }

    public function productItems(): array
    {
        return [
            ProductData::from([
                'name'       => fake()->words(2, true),
                'quantity'   => fake()->numberBetween(1, 10),
                'price'      => $this->payment->price,
                'category'   => fake()->creditCardType,
                'vendorCode' => fake()->randomLetter(),
            ]),
        ];
    }
}
