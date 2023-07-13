<?php

declare(strict_types=1);

namespace Tests\Fixtures\Providers;

use Cashbox\Core\Providers\ServiceProvider;

class TestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
