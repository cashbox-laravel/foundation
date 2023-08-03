<?php

/**
 * This file is part of the "cashbox/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://cashbox.city
 */

declare(strict_types=1);

use Cashbox\Core\Console\Commands\Refund;
use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentRefundedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Cashbox\Core\Events\PaymentWaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the refund of fresh payments', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 2);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    fakeEvents();

    artisan(Refund::class);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertNothingDispatched();
});

it('checks the refund on time', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 2);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    subHour($payment1, $payment2, $payment3);

    fakeEvents();

    artisan(Refund::class);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::refund);
    expect($payment3)->toBeStatus(StatusEnum::refund);

    Event::assertDispatchedTimes(PaymentRefundedEvent::class, 2);
});

it('check forced refund', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 2);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    fakeEvents();

    artisan(Refund::class, ['--force' => true]);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::refund);
    expect($payment3)->toBeStatus(StatusEnum::refund);

    Event::assertDispatchedTimes(PaymentRefundedEvent::class, 2);
});

it('checks the refund of payment by ID', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 2);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    fakeEvents();

    artisan(Refund::class, [
        'payment' => $payment2->id,
    ]);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::refund);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentRefundedEvent::class, 1);
});

it('check forced refund by ID', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::cash);
    $payment3 = createPayment(TypeEnum::tinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::success);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 2);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    fakeEvents();

    artisan(Refund::class, [
        'payment' => $payment2->id,
        '--force' => true,
    ]);

    expect($payment1)->toBeStatus(StatusEnum::new);
    expect($payment2)->toBeStatus(StatusEnum::refund);
    expect($payment3)->toBeStatus(StatusEnum::success);

    Event::assertDispatchedTimes(PaymentRefundedEvent::class, 1);
    Event::assertDispatchedTimes(PaymentWaitRefundEvent::class, 0);
});
