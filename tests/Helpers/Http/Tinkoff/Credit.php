<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Http;

function fakeTinkoffCreditHttp(string $info = 'signed'): void
{
    Http::fake([
        'https://forma.tinkoff.ru/api/partners/v2/orders/create-demo' => Http::response([
            'id'   => 'demo-0f0f0f0f-0f0f-0f0f-0f0f-0f0f0f0f0f0f',
            'link' => fake()->imageUrl,
        ]),

        'https://forma.tinkoff.ru/api/partners/v2/orders/*/info' => Http::response([
            'id'         => fake()->uuid,
            'status'     => $info,
            'created_at' => fake()->unixTime,
        ]),

        'https://forma.tinkoff.ru/api/partners/v2/orders/*/commit' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'approved',
            'created_at' => fake()->unixTime,
        ]),

        'https://forma.tinkoff.ru/api/partners/v2/orders/*/cancel' => Http::response([
            'id'         => fake()->uuid,
            'status'     => 'canceled',
            'created_at' => fake()->unixTime,
        ]),
    ]);
}
