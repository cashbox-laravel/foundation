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
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the wait to refund', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::outside);

    expect($payment->type)->toBe(TypeEnum::outside);
    expect($payment->status)->toBe(StatusEnum::new);

    $payment->update(['status' => StatusEnum::waitRefund]);

    expect($payment->status)->toBe(StatusEnum::waitRefund);

    $payment->update(['status' => StatusEnum::refund]);

    expect($payment->status)->toBe(StatusEnum::refund);

    expect($payment)->toBeDoesntHaveCashbox();

    Event::assertNotDispatched(PaymentCreatedEvent::class);
    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentRefundedEvent::class);
    Event::assertNotDispatched(PaymentSuccessEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);
});

it('checks the refund', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::outside);

    expect($payment->type)->toBe(TypeEnum::outside);
    expect($payment->status)->toBe(StatusEnum::new);

    $payment->update(['status' => StatusEnum::refund]);

    expect($payment->status)->toBe(StatusEnum::refund);

    expect($payment)->toBeDoesntHaveCashbox();

    Event::assertNotDispatched(PaymentCreatedEvent::class);
    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentRefundedEvent::class);
    Event::assertNotDispatched(PaymentSuccessEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);
});
