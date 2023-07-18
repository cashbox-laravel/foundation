<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;
use Tests\Fixtures\App\Models\PaymentModel;

function assertHasCashbox(PaymentModel $payment): void
{
    expect($payment->cashbox()->exists())->toBeTrue();
}

function assertDoesntHaveCashbox(PaymentModel $payment): void
{
    expect($payment->cashbox()->doesntExist())->toBeTrue();
}

expect()->extend('toBeUrl', function (string $message = '') {
    Assert::assertTrue(Str::isUrl($this->value), $message);

    return $this;
});
