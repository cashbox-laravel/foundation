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

use CashierProvider\Core\Resources\Model;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ModelResource extends Model
{
    protected function clientId(): string
    {
        return TestCase::TERMINAL_KEY;
    }

    protected function clientSecret(): string
    {
        return TestCase::TOKEN;
    }

    protected function paymentId(): string
    {
        return TestCase::PAYMENT_ID;
    }

    protected function sum(): float
    {
        return TestCase::SUM;
    }

    protected function currency(): string
    {
        return TestCase::CURRENCY;
    }

    protected function createdAt(): Carbon
    {
        return Carbon::parse(TestCase::CREATED_AT);
    }
}
