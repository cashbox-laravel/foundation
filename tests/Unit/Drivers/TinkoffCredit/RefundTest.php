<?php

declare(strict_types=1);

use Cashbox\Core\Console\Commands\Refund;
use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentRefundedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('refund for new', function () {
    fakeEvents();
    fakeTinkoffCreditHttp('new');

    $payment = createPayment(TypeEnum::tinkoffCredit);

    expect($payment->type)->toBe(TypeEnum::tinkoffCredit);
    expect($payment->status)->toBe(StatusEnum::new);

    expect($payment)->toBeHasCashbox();

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 0);

    fakeEvents();

    artisan(Refund::class);

    // time has not yet come to refund.
    expect($payment)->toBeStatus(StatusEnum::new);

    Event::assertNothingDispatched();

    subHour($payment);

    fakeEvents();

    artisan(Refund::class);

    expect($payment)->toBeStatus(StatusEnum::refund);

    Event::assertDispatchedTimes(PaymentRefundedEvent::class);
});

it('refund for success', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment = createPayment(TypeEnum::tinkoffCredit);

    expect($payment->type)->toBe(TypeEnum::tinkoffCredit);
    expect($payment->status)->toBe(StatusEnum::new);

    expect($payment)->toBeHasCashbox();

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class);

    fakeEvents();

    artisan(Refund::class);

    // time has not yet come to refund.
    expect($payment)->toBeStatus(StatusEnum::success);

    Event::assertNothingDispatched();

    subHour($payment);

    fakeEvents();

    artisan(Refund::class);

    expect($payment)->toBeStatus(StatusEnum::refund);

    Event::assertDispatchedTimes(PaymentRefundedEvent::class);
});