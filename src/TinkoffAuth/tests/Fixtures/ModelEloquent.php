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
 * @see https://github.com/cashbox/foundation
 */

declare(strict_types=1);

namespace Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/**
 * @property string $client_id
 * @property string $client_secret
 * @property string $payment_id
 * @property string $sum
 * @property string $currency
 * @property string $created_at
 */
class ModelEloquent extends Model
{
    public $timestamps = false;

    protected function getClientIdAttribute(): string
    {
        return TestCase::TERMINAL_KEY;
    }

    protected function getClientSecretAttribute(): string
    {
        return TestCase::TOKEN;
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
