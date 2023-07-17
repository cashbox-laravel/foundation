<?php

declare(strict_types=1);

use Cashbox\Core\Facades\Config;
use Illuminate\Support\Facades\Http;

function fakeHttpTinkoffCreditCreate(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/create-demo' => Http::response([
            'id'   => 'demo-0f0f0f0f-0f0f-0f0f-0f0f-0f0f0f0f0f0f',
            'link' => fake()->url,
        ]),
    ]);
}

function fakeHttpTinkoffCreditInfo(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/{orderNumber}/info' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'new',
            'created_at' => now()->toIso8601String(),
            'demo'       => ! Config::isProduction(),
            'committed'  => true,
        ]),
    ]);
}

function fakeHttpTinkoffCreditCommit(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/{orderNumber}/commit' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'approved',
            'created_at' => now()->toIso8601String(),
            'demo'       => ! Config::isProduction(),
            'committed'  => true,
        ]),
    ]);
}

function fakeHttpTinkoffCreditCancel(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/{orderNumber}/cancel' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'canceled',
            'created_at' => now()->toIso8601String(),
            'demo'       => ! Config::isProduction(),
            'committed'  => true,
        ]),
    ]);
}
