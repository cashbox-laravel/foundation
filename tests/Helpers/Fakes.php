<?php

declare(strict_types=1);

use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentFailedEvent;
use Cashbox\Core\Events\PaymentRefundedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Cashbox\Core\Events\PaymentWaitRefundEvent;
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
        PaymentCreatedEvent::class,
        PaymentFailedEvent::class,
        PaymentRefundedEvent::class,
        PaymentSuccessEvent::class,
        PaymentWaitRefundEvent::class,
    ]);
}
