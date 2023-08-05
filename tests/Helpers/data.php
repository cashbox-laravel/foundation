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

use Cashbox\Core\Data\Config\DriverData;
use Cashbox\Core\Data\Config\Drivers\CredentialsData;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Tests\Fixtures\Drivers\Driver;
use Tests\Fixtures\Resources\TemplateAuthResource;

function dataCast(Cast|string $cast, mixed $value, ?DataProperty $property = null, array $context = []): mixed
{
    $property ??= fakeDataProperty();

    $cast = is_string($cast) ? new $cast() : $cast;

    return $cast->cast($property, $value, $context);
}

function driverData(): DriverData
{
    return DriverData::from([
        'driver'      => Driver::class,
        'resource'    => TemplateAuthResource::class,
        'credentials' => CredentialsData::from([
            'clientId'     => 'qwerty',
            'clientSecret' => 'qwerty123',
        ]),
    ]);
}
