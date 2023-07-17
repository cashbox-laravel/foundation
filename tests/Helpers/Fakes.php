<?php

declare(strict_types=1);

use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Spatie\LaravelData\Support\DataProperty;
use Tests\Fixtures\Data\FakeData;

function fakeDataProperty(): DataProperty
{
    $reflection = new ReflectionProperty(FakeData::class, 'foo');

    return DataProperty::create($reflection);
}

function fakeEvents(): void
{
    Event::fake([
        CreatedEvent::class,
        FailedEvent::class,
        RefundedEvent::class,
        SuccessEvent::class,
        WaitRefundEvent::class,
    ]);
}
