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

use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;

function createPayment(TypeEnum $type, ?int $price = null): PaymentModel
{
    $price ??= fake()->randomNumber(4);

    return PaymentModel::factory()->create(compact('type', 'price'));
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
