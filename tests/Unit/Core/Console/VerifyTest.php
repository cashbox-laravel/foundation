<?php

declare(strict_types=1);

use Cashbox\Core\Console\Commands\Verify;
use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('full verification', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::tinkoffCredit);
    $payment5 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);
    expect($payment4)->toBeStatus(StatusEnum::new);
    expect($payment5)->toBeStatus(StatusEnum::new);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    setStatus(StatusEnum::new, $payment2);

    fakeEvents();

    artisan(Verify::class);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);
    expect($payment4)->toBeStatus(StatusEnum::success);
    expect($payment5)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 3);
});

it('verify by ID', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::tinkoffCredit);
    $payment5 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);
    expect($payment4)->toBeStatus(StatusEnum::new);
    expect($payment5)->toBeStatus(StatusEnum::new);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    setStatus(StatusEnum::new, $payment2, $payment3);

    fakeEvents();

    artisan(Verify::class, [
        'payment' => $payment2->id,
    ]);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::new);
    expect($payment4)->toBeStatus(StatusEnum::new);
    expect($payment5)->toBeStatus(StatusEnum::new);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 1);
});

it('verify by ID with force flag', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::tinkoffCredit);
    $payment5 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);
    expect($payment4)->toBeStatus(StatusEnum::new);
    expect($payment5)->toBeStatus(StatusEnum::new);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    setStatus(StatusEnum::new, $payment2, $payment3);

    fakeEvents();

    artisan(Verify::class, [
        'payment' => $payment2->id,
        '--force' => true,
    ]);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::new);
    expect($payment4)->toBeStatus(StatusEnum::new);
    expect($payment5)->toBeStatus(StatusEnum::new);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 1);
});
