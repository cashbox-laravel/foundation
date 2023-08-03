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
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('refund for new', function () {
    fakeEvents();
    fakeTinkoffOnlineHttp('new');

    $payment = createPayment(TypeEnum::tinkoffOnline);

    expect($payment->type)->toBe(TypeEnum::tinkoffOnline);
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
    fakeTinkoffOnlineHttp();

    $payment = createPayment(TypeEnum::tinkoffOnline);

    expect($payment->type)->toBe(TypeEnum::tinkoffOnline);
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
