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

it('checks the verify', function () {
    fakes();

    $payment = createPayment(TypeEnum::cash);

    expect($payment->type)->toBe(TypeEnum::cash);
    expect($payment->status)->toBe(StatusEnum::new);

    assertHasCashbox($payment);

    // verify
    $payment->refresh()->updateQuietly([
        'status' => StatusEnum::new,
    ]);

    $payment->cashboxJob()->verify();

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::success);

    Event::assertDispatchedTimes(CreatedEvent::class);
    Event::assertDispatchedTimes(SuccessEvent::class, 2);

    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Http::assertNothingSent();
});
