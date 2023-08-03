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

namespace Tests\Fixtures\App\Enums;

enum StatusEnum: int
{
    case new        = 0;
    case success    = 1;
    case waitRefund = 2;
    case refund     = 3;
    case failed     = 4;
}
