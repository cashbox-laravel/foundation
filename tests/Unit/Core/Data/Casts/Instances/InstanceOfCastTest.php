<?php

declare(strict_types=1);

use Cashbox\Core\Billable;
use Cashbox\Core\Data\Casts\Instances\InstanceOfCast;
use Cashbox\Core\Exceptions\Internal\IncorrectModelException;
use Cashbox\Core\Services\Driver;
use Tests\Fixtures\Data\FakeData;
use Tests\Fixtures\Models\PaymentModel;

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
