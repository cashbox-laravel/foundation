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

use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentFailedEvent;
use Cashbox\Core\Events\PaymentRefundedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Cashbox\Core\Events\PaymentWaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the manual refund', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::cash);

    expect($payment->type)->toBe(TypeEnum::cash);
    expect($payment->status)->toBe(StatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::success);

    // refund
    $payment->refresh();
    $payment->cashboxJob()->refund();

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::refund);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class);
    Event::assertDispatchedTimes(PaymentRefundedEvent::class);

    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);

    Http::assertNothingSent();
});

it('checks the auto refund', function () {
    fakeEvents();
    forgetConfig();

    config(['cashbox.auto_refund.enabled' => true]);

    $payment = createPayment(TypeEnum::cash);

    expect($payment->type)->toBe(TypeEnum::cash);
    expect($payment->status)->toBe(StatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();
    expect($payment->status)->toBe(StatusEnum::refund);

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);
    Event::assertDispatchedTimes(PaymentSuccessEvent::class);
    Event::assertDispatchedTimes(PaymentRefundedEvent::class);

    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);

    Http::assertNothingSent();
});
