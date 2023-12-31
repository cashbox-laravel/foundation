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

namespace Tests\database\seeders;

use Illuminate\Database\Seeder;
use Tests\Fixtures\Factories\Payment;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        Payment::create();
    }
}
