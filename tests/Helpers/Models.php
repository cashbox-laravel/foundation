<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;

function createPayment(TypeEnum $type): PaymentModel
{
    return PaymentModel::factory()->create(compact('type'));
}

function subHour(Model ...$payments): void
{
    foreach ($payments as $payment) {
        $payment->created_at = now()->subHour();

        $payment->saveQuietly();
    }
}

function setStatus(StatusEnum $status, Model ...$payments): void
{
    foreach ($payments as $payment) {
        $payment->status = $status;

        $payment->saveQuietly();
    }
}
