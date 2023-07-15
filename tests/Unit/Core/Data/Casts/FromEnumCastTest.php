<?php

declare(strict_types=1);

use Cashbox\Core\Data\Casts\FromEnumCast;
use Tests\Fixtures\Enums\TypeEnum;

it('must be a enum', function () {
    expect(
        dataCast(FromEnumCast::class, TypeEnum::cash)
    )->toBe(TypeEnum::cash->value)->toBeString();

    expect(
        dataCast(FromEnumCast::class, TypeEnum::outside)
    )->toBe(TypeEnum::outside->value)->toBeString();
});

it('must be a empty', function () {
    expect(
        dataCast(FromEnumCast::class, null)
    )->toBeNull();
});
