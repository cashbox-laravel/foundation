<?php

declare(strict_types=1);

use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Cashbox\Core\Jobs\RefundJob;
use Cashbox\Core\Jobs\StartJob;
use Cashbox\Core\Jobs\VerifyJob;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the wait to refund', function () {
    fakes();

    $payment = createPayment(TypeEnum::outside);

    expect($payment->type)->toBe(TypeEnum::outside);
    expect($payment->status)->toBe(StatusEnum::new);

    $payment->update(['status' => StatusEnum::waitRefund]);

    expect($payment->status)->toBe(StatusEnum::waitRefund);

    $payment->update(['status' => StatusEnum::refund]);

    expect($payment->status)->toBe(StatusEnum::refund);

    assertDoesntHaveCashbox($payment);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Queue::assertNotPushed(StartJob::class);
    Queue::assertNotPushed(VerifyJob::class);
    Queue::assertNotPushed(RefundJob::class);
});

it('checks the refund', function () {
    fakes();

    $payment = createPayment(TypeEnum::outside);

    expect($payment->type)->toBe(TypeEnum::outside);
    expect($payment->status)->toBe(StatusEnum::new);

    $payment->update(['status' => StatusEnum::refund]);

    expect($payment->status)->toBe(StatusEnum::refund);

    assertDoesntHaveCashbox($payment);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Queue::assertNotPushed(StartJob::class);
    Queue::assertNotPushed(VerifyJob::class);
    Queue::assertNotPushed(RefundJob::class);
});
