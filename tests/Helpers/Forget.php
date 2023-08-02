<?php

declare(strict_types=1);

use Cashbox\Core\Data\Config\ConfigData;
use Cashbox\Core\Facades\Config;
use Illuminate\Http\Client\Factory;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;

function forget(string $class, Facade|string|null $facade = null): void
{
    if ($facade) {
        $facade::clearResolvedInstances();
    }

    App::forgetInstance($class);
}

function forgetConfig(): void
{
    forget(ConfigData::class, Config::class);
}

function forgetHttp(): void
{
    forget(Factory::class, Http::class);
}