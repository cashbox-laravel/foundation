<?php

declare(strict_types=1);

use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the manual refund', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::cash);

    expect($payment->type)->toBe(TypeEnum::cash);
    expect($payment->status)->toBe(StatusEnum::new);

    assertHasCashbox($payment);

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::success);

    // refund
    $payment->refresh();
    $payment->cashboxJob()->refund();

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::refund);

    Event::assertDispatchedTimes(CreatedEvent::class);
    Event::assertDispatchedTimes(SuccessEvent::class);
    Event::assertDispatchedTimes(RefundedEvent::class);

    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Http::assertNothingSent();
});

it('checks the auto refund', function () {
    fakeEvents();
    forgetConfig();

    config(['cashbox.auto_refund.enabled' => true]);

    $payment = createPayment(TypeEnum::cash);

    expect($payment->type)->toBe(TypeEnum::cash);
    expect($payment->status)->toBe(StatusEnum::new);

    assertHasCashbox($payment);

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::refund);

    Event::assertDispatchedTimes(CreatedEvent::class);
    Event::assertDispatchedTimes(SuccessEvent::class);
    Event::assertDispatchedTimes(RefundedEvent::class);

    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Http::assertNothingSent();
});
