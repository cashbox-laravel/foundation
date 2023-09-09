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

namespace Tests\Fixtures\App\Providers;

use Illuminate\Support\ServiceProvider;
use Tests\Fixtures\App\Models\PaymentModel;
use Tests\Fixtures\App\Observers\PaymentObserver;

class TestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        PaymentModel::observe(PaymentObserver::class);
    }
}
