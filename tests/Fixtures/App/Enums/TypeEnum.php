<?php

declare(strict_types=1);

namespace Tests\Fixtures\App\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string cash()
 * @method static string outside()
 * @method static string tinkoffCredit()
 */
enum TypeEnum: string
{
    use InvokableCases;

    case cash          = 'cash';
    case outside       = 'outside';
    case tinkoffCredit = 'tinkoff_credit';
}
