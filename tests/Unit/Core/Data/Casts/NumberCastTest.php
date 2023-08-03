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

use Cashbox\Core\Data\Casts\NumberCast;

it('must be a valid values', function (int $value) {
    expect(
        dataCast(NumberCast::class, $value)
    )->toBeInt()->toBe($value);
})->with(
    fn () => range(0, 20)
);

it('should check the min value', function (int $value) {
    expect(
        dataCast(NumberCast::class, $value)
    )->toBeInt()->toBe(0);
})->with(
    fn () => range(-20, 0)
);

it('should check the max value', function (int $value) {
    expect(
        dataCast(NumberCast::class, $value)
    )->toBeInt()->toBe(100);
})->with(
    fn () => range(100, 120)
);
