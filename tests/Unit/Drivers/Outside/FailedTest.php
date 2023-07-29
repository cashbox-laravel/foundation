<?php

declare(strict_types=1);

use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the failed', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::outside);

    expect($payment->type)->toBe(TypeEnum::outside);
    expect($payment->status)->toBe(StatusEnum::new);

    $payment->update(['status' => StatusEnum::failed]);

    expect($payment->status)->toBe(StatusEnum::failed);

    expect($payment)->toBeDoesntHaveCashbox();

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);
});
