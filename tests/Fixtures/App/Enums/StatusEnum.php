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
    case New        = 0;
    case Success    = 1;
    case WaitRefund = 2;
    case Refund     = 3;
    case Failed     = 4;
}
