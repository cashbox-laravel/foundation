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
