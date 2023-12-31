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

namespace CashierProvider\Core\Concerns\Config\Payment;

use CashierProvider\Core\Data\Config\Payment\PaymentData;
use CashierProvider\Core\Facades\Config;

trait Payments
{
    protected static function payment(): PaymentData
    {
        return Config::payment();
    }
}
