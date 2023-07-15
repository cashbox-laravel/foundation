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

it('checks the create', function () {
    fakes();

    expect(paymentTable()->count())->toBe(0);

    $payment = createPayment(TypeEnum::outside)->refresh();

    expect($payment->price)->toBeInt();
    expect($payment->type)->toBe(TypeEnum::outside);
    expect($payment->status)->toBe(StatusEnum::new);

    expect(paymentTable()->count())->toBe(1);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Queue::assertNotPushed(StartJob::class);
    Queue::assertNotPushed(VerifyJob::class);
    Queue::assertNotPushed(RefundJob::class);
});
