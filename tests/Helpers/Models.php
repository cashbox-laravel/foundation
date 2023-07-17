<?php

declare(strict_types=1);

use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;

function createPayment(TypeEnum $type, ?int $price = null): PaymentModel
{
    $price ??= random_int(1, 50000);

    return PaymentModel::create(compact('type', 'price'));
}
