<?php

declare(strict_types=1);

namespace Tests\Fixtures\Enums;

use ArchTech\Enums\InvokableCases;

/**
 * @method static string cash()
 */
enum TypeEnum: string
{
    use InvokableCases;

    case cash = 'cash';
}
