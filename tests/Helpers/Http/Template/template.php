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

function fakeTemplateHttp(string $info = 'CONFIRMED'): void
{
    Http::fake([
        'https://example.com/v1/init' => Http::response([
            'Success'   => true,
            'ErrorCode' => 0,
            'Status'    => 'NEW',
            'PaymentId' => fake()->randomNumber(),
            'Data'      => fake()->imageUrl,
        ]),

        'https://example.com/v1/state' => Http::response([
            'Success'   => true,
            'ErrorCode' => 0,
            'Status'    => $info,
            'PaymentId' => fake()->randomNumber(),
        ]),

        'https://example.com/v1/cancel' => Http::response([
            'Success'   => true,
            'ErrorCode' => 0,
            'Status'    => 'REFUNDED',
            'PaymentId' => fake()->randomNumber(),
        ]),
    ]);
}

function fakeTemplateInvalidHttp(): void
{
    Http::fake([
        'https://example.com/v1/init' => Http::response([
            'Success'   => false,
            'ErrorCode' => 403,
            'Message'   => 'Invalid parameters.',
            'Details'   => 'Terminal not found.',
        ]),

        'https://example.com/v1/state' => Http::response([
            'Success'   => false,
            'ErrorCode' => 403,
            'Message'   => 'Invalid parameters.',
            'Details'   => 'Terminal not found.',
        ]),

        'https://example.com/v1/cancel' => Http::response([
            'Success'   => false,
            'ErrorCode' => 403,
            'Message'   => 'Invalid parameters.',
            'Details'   => 'Terminal not found.',
        ]),
    ]);
}
