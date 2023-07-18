<?php

declare(strict_types=1);

use Cashbox\Core\Facades\Config;
use Illuminate\Support\Facades\Http;

function httpTinkoffCreditCreate(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/create-demo' => Http::response([
            'id'   => 'demo-0f0f0f0f-0f0f-0f0f-0f0f-0f0f0f0f0f0f',
            'link' => fake()->imageUrl,
        ]),
    ]);
}

function httpTinkoffCreditInfo(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/*/info' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'new',
            'created_at' => now()->toIso8601String(),
            'demo'       => ! Config::isProduction(),
            'committed'  => true,
        ]),
    ]);
}

function httpTinkoffCreditCommit(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/*/commit' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'approved',
            'created_at' => now()->toIso8601String(),
            'demo'       => ! Config::isProduction(),
            'committed'  => true,
        ]),
    ]);
}

function httpTinkoffCreditCancel(): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/*/cancel' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'canceled',
            'created_at' => now()->toIso8601String(),
            'demo'       => ! Config::isProduction(),
            'committed'  => true,
        ]),
    ]);
}
