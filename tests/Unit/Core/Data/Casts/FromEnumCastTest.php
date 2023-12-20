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

use Cashbox\Core\Data\Casts\FromEnumCast;
use Tests\Fixtures\App\Enums\TypeEnum;

it('must be a enum', function () {
    expect(
        dataCast(FromEnumCast::class, TypeEnum::Cash)
    )->toBe(TypeEnum::Cash->value)->toBeString();

    expect(
        dataCast(FromEnumCast::class, TypeEnum::Outside)
    )->toBe(TypeEnum::Outside->value)->toBeString();
});

it('must be a empty', function () {
    expect(
        dataCast(FromEnumCast::class, null)
    )->toBeNull();
});
