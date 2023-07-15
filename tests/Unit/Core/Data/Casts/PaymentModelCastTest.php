<?php

declare(strict_types=1);

use Cashbox\Core\Billable;
use Cashbox\Core\Exceptions\Internal\IncorrectPaymentModelException;
use Cashbox\Core\Facades\Config;
use Tests\Fixtures\Data\FakeData;
use Tests\Fixtures\Models\PaymentModel;

it('should check the correct payment model', function () {
    expect(
        Config::payment()->model
    )->toBe(PaymentModel::class);
});

it('should check the incorrect payment model', function () {
    forgetConfig();

    config(['cashbox.payment.model' => FakeData::class]);

    Config::payment()->model;
})
    ->throws(IncorrectPaymentModelException::class)
    ->expectExceptionMessage(sprintf('The "%s" class must implement "%s".', FakeData::class, Billable::class));
