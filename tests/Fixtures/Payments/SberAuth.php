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

use Cashbox\Core\Enums\CurrencyEnum;
use Cashbox\Core\Resources\Resource;

class SberAuth extends Resource
{
    public function currency(): int
    {
        return CurrencyEnum::USD->value;
    }

    public function sum(): int
    {
        return 1000;
    }
}
