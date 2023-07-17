<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Tests\Fixtures\App\Models\PaymentModel;

function assertHasCashbox(PaymentModel $payment): void
{
    expect($payment->cashbox()->exists())->toBeTrue();
}

function assertDoesntHaveCashbox(PaymentModel $payment): void
{
    expect($payment->cashbox()->doesntExist())->toBeTrue();
}

function assertIsUrl(string $value): void
{
    expect(Str::isUrl($value))->toBeTrue();
}
