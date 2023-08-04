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

use Illuminate\Support\Facades\Http;

function fakeTinkoffOnlineHttp(string $info = 'CONFIRMED'): void
{
    Http::fake([
        'https://securepay.tinkoff.ru/v2/Init' => Http::response([
            'Success'     => true,
            'ErrorCode'   => 0,
            'TerminalKey' => fake()->word,
            'Status'      => 'NEW',
            'PaymentId'   => fake()->randomNumber(),
            'OrderId'     => fake()->randomNumber(),
            'Amount'      => fake()->randomNumber(),
            'PaymentURL'  => fake()->imageUrl,
        ]),

        'https://securepay.tinkoff.ru/v2/GetState' => Http::response([
            'Success'     => true,
            'ErrorCode'   => 0,
            'Message'     => 'OK',
            'TerminalKey' => fake()->word,
            'Status'      => $info,
            'PaymentId'   => fake()->randomNumber(),
            'OrderId'     => fake()->randomNumber(),
            'Amount'      => fake()->randomNumber(),
        ]),

        'https://securepay.tinkoff.ru/v2/Cancel' => Http::response([
            'Success'        => true,
            'ErrorCode'      => 0,
            'Message'        => 'OK',
            'TerminalKey'    => fake()->word,
            'Status'         => 'REFUNDED',
            'PaymentId'      => fake()->randomNumber(),
            'OrderId'        => fake()->randomNumber(),
            'OriginalAmount' => fake()->randomNumber(),
            'NewAmount'      => 0,
        ]),
    ]);
}

function fakeTinkoffOnlineInvalidHttp(): void
{
    Http::fake([
        'https://securepay.tinkoff.ru/v2/Init' => Http::response([
            'Success'   => false,
            'ErrorCode' => '501',
            'Message'   => 'Неверные параметры.',
            'Details'   => 'Терминал не найден.',
        ]),

        'https://securepay.tinkoff.ru/v2/GetState' => Http::response([
            'Success'   => false,
            'ErrorCode' => '501',
            'Message'   => 'Неверные параметры.',
            'Details'   => 'Терминал не найден.',
        ]),

        'https://securepay.tinkoff.ru/v2/Cancel' => Http::response([
            'Success'   => false,
            'ErrorCode' => '501',
            'Message'   => 'Неверные параметры.',
            'Details'   => 'Терминал не найден.',
        ]),
    ]);
}
