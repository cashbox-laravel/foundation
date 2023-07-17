<?php

declare(strict_types=1);

use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;

function dataCast(Cast|string $cast, mixed $value, ?DataProperty $property = null, array $context = []): mixed
{
    $property ??= fakeDataProperty();

    $cast = is_string($cast) ? new $cast() : $cast;

    return $cast->cast($property, $value, $context);
}
