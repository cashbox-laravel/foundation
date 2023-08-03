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

namespace Tests\Fixtures\Payments;

use Cashbox\Cash\Resources\CashResource;
use Cashbox\Core\Enums\CurrencyEnum;

class Cash extends CashResource
{
    public function currency(): CurrencyEnum
    {
        return CurrencyEnum::USD;
    }

    public function sum(): int
    {
        return $this->payment->price;
    }
}
