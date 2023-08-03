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

use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class)->group('core')->in('Unit/Core');
uses(TestCase::class)->group('cash')->in('Unit/Drivers/Cash');
uses(TestCase::class)->group('outside')->in('Unit/Drivers/Outside');
uses(TestCase::class)->group('tinkoff-auth')->in('Unit/Drivers/TinkoffAuth');
uses(TestCase::class)->group('tinkoff-credit')->in('Unit/Drivers/TinkoffCredit');
uses(TestCase::class)->group('tinkoff-online')->in('Unit/Drivers/TinkoffOnline');
uses(TestCase::class)->group('tinkoff-qr')->in('Unit/Drivers/TinkoffQrCode');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function artisan(string $command, array $parameters = []): void
{
    Artisan::call($command, $parameters);
}
