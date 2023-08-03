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

use Cashbox\Core\Billable;
use Cashbox\Core\Data\Casts\Instances\InstanceOfCast;
use Cashbox\Core\Exceptions\Internal\IncorrectModelException;
use Cashbox\Core\Services\Driver;
use Tests\Fixtures\App\Models\PaymentModel;
use Tests\Fixtures\Data\FakeData;

it('must be correct', function () {
    expect(
        dataCast(new InstanceOfCast(Billable::class, RuntimeException::class), PaymentModel::class)
    )->toBe(PaymentModel::class);
});

it('must be incorrect', function () {
    dataCast(new InstanceOfCast(Driver::class, IncorrectModelException::class), FakeData::class);
})
    ->throws(IncorrectModelException::class)
    ->expectExceptionMessage(sprintf('The "%s" class must implement "%s".', FakeData::class, Driver::class));
