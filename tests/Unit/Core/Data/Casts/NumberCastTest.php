<?php

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
