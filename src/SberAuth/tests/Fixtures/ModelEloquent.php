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
 * @see https://github.com/cashbox-laravel/foundation
 */

declare(strict_types=1);

namespace Tests\Fixtures;

use CashierProvider\Core\Billable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * @property string $client_id
 * @property string $client_secret
 * @property string $created_at
 * @property string $currency
 * @property string $member_id
 * @property string $payment_id
 * @property string $sum
 */
class ModelEloquent extends Model
{
    use Billable;

    public $timestamps = false;

    protected function getClientIdAttribute(): string
    {
        return config('cashier.drivers.sber_qr.client_id');
    }

    protected function getClientSecretAttribute(): string
    {
        return config('cashier.drivers.sber_qr.client_secret');
    }

    protected function getMemberIdAttribute(): string
    {
        return config('cashier.drivers.sber_qr.member_id');
    }

    protected function getPaymentIdAttribute(): string
    {
        return TestCase::PAYMENT_ID;
    }

    protected function getSumAttribute(): float
    {
        return TestCase::SUM;
    }

    protected function getCurrencyAttribute(): string
    {
        return TestCase::CURRENCY;
    }

    protected function getCreatedAtAttribute(): Carbon
    {
        return Carbon::parse(TestCase::CREATED_AT);
    }
}
