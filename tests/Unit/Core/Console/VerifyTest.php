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

use Cashbox\Core\Console\Commands\Verify;
use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('direct verification', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment1 = createPayment(TypeEnum::Outside);
    $payment2 = createPayment(TypeEnum::Cash);
    $payment3 = createPayment(TypeEnum::Cash);
    $payment4 = createPayment(TypeEnum::TinkoffCredit);
    $payment5 = createPayment(TypeEnum::TinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::Success);
    expect($payment5)->toBeStatus(StatusEnum::Success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 4);

    setStatus(StatusEnum::New, $payment2, $payment4);

    fakeEvents();

    expect($payment2)->toBeStatus(StatusEnum::New);
    expect($payment4)->toBeStatus(StatusEnum::New);

    artisan(Verify::class);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::Success);
    expect($payment5)->toBeStatus(StatusEnum::Success);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);
});

it('delayed verification', function () {
    fakeEvents();
    fakeTinkoffCreditHttp('new');

    $payment1 = createPayment(TypeEnum::Outside);
    $payment2 = createPayment(TypeEnum::Cash);
    $payment3 = createPayment(TypeEnum::Cash);
    $payment4 = createPayment(TypeEnum::TinkoffCredit);
    $payment5 = createPayment(TypeEnum::TinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    fakeEvents();

    artisan(Verify::class);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 0);

    fakeEvents();

    artisan(Verify::class);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 0);
});

it('verify by ID', function () {
    fakeEvents();
    fakeTinkoffCreditHttp('new');

    $payment1 = createPayment(TypeEnum::Outside);
    $payment2 = createPayment(TypeEnum::Cash);
    $payment3 = createPayment(TypeEnum::Cash);
    $payment4 = createPayment(TypeEnum::TinkoffCredit);
    $payment5 = createPayment(TypeEnum::TinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    setStatus(StatusEnum::New, $payment2, $payment3);

    fakeEvents();

    artisan(Verify::class, [
        'payment' => $payment2->id,
    ]);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::New);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 1);
});

it('verify by ID with force flag', function () {
    fakeEvents();
    fakeTinkoffCreditHttp('new');

    $payment1 = createPayment(TypeEnum::Outside);
    $payment2 = createPayment(TypeEnum::Cash);
    $payment3 = createPayment(TypeEnum::Cash);
    $payment4 = createPayment(TypeEnum::TinkoffCredit);
    $payment5 = createPayment(TypeEnum::TinkoffCredit);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::Success);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 4);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 2);

    setStatus(StatusEnum::New, $payment2, $payment3);

    fakeEvents();

    artisan(Verify::class, [
        'payment' => $payment2->id,
        '--force' => true,
    ]);

    expect($payment1)->toBeStatus(StatusEnum::New);
    expect($payment2)->toBeStatus(StatusEnum::Success);
    expect($payment3)->toBeStatus(StatusEnum::New);
    expect($payment4)->toBeStatus(StatusEnum::New);
    expect($payment5)->toBeStatus(StatusEnum::New);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class, 0);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 1);
});
