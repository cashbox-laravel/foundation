<?php

declare(strict_types=1);

use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;

function createPayment(TypeEnum $type): PaymentModel
{
    return PaymentModel::factory()->create(compact('type'));
}
