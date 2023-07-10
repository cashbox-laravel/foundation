<?php

/**
 * This file is part of the "cashier-provider/core" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://github.com/cashier-provider
 */

namespace CashierProvider\Core\Data\Models;

use CashierProvider\Core\Concerns\Config\Payment\Payments;
use CashierProvider\Core\Enums\StatusEnum;
use Spatie\LaravelData\Data;

abstract class InfoData extends Data
{
    use Payments;

    public int|string|null $status;

    public function statusToEnum(): StatusEnum
    {
        return static::payment()->status->toEnum($this->status);
    }
}
