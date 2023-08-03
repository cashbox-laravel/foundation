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

use Cashbox\Core\Support\Arr;

it('checks filter', function () {
    expect(
        Arr::filter([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'sdf' => null,
            'qwe' => [
                'rty',
                'asd',
                null,
            ],
            'asd' => [
                100   => 0,
                200   => 1,
                300   => 'qwe',
                '400' => 'asd',
                500   => null,
                '600' => null,
            ],
        ])
    )->toBe([
        'foo' => 'Foo',
        'bar' => 'Bar',
        'qwe' => [
            'rty',
            'asd',
        ],
        'asd' => [
            100   => 0,
            200   => 1,
            300   => 'qwe',
            '400' => 'asd',
        ],
    ]);
});

it('checks merge', function () {
    expect(
        Arr::merge([
            'foo' => 'Foo',
            'bar' => 'Bar',
            'qwe' => [
                'qwe' => 'rty',
                'asd' => null,
            ],
        ], [
            'foo' => 'Fooo',
            'qwe' => [
                'qwe' => null,
                'asd' => 'fgh',
            ],
        ])
    )->toBe([
        'foo' => 'Fooo',
        'bar' => 'Bar',
        'qwe' => [
            'qwe' => 'rty',
            'asd' => 'fgh',
        ],
    ]);
});
