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

use ArchTech\Enums\InvokableCases;

/**
 * @method static string cash()
 * @method static string outside()
 * @method static string tinkoffCredit()
 * @method static string tinkoffOnline()
 */
enum TypeEnum: string
{
    use InvokableCases;

    case cash          = 'cash';
    case outside       = 'outside';
    case tinkoffCredit = 'tinkoff_credit';
    case tinkoffOnline = 'tinkoff_online';
}
