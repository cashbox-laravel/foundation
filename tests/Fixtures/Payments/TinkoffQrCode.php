<?php

/**
 * This file is part of the "cashbox/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://cashbox.city
 */

declare(strict_types=1);

namespace Tests\Fixtures\Payments;

use Cashbox\Core\Enums\CurrencyEnum;
use Cashbox\Tinkoff\Credit\Data\ContactData;
use Cashbox\Tinkoff\Credit\Data\ProductData;
use Cashbox\Tinkoff\QrCode\Resources\TinkoffQrCodeResource;

class TinkoffQrCode extends TinkoffQrCodeResource
{
    public function currency(): int
    {
        return CurrencyEnum::USD->value;
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
