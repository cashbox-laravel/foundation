<?php

declare(strict_types=1);

namespace Tests\Fixtures\Data;

use Spatie\LaravelData\Data;

class FakeData extends Data
{
    public ?string $foo = null;
}
