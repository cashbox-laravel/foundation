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
use Cashbox\Core\Exceptions\External\BadRequestHttpException;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the progress', function () {
    fakeEvents();
    fakeTinkoffQrCodeHttp('NEW');

    $payment = createPayment(TypeEnum::tinkoffQrCode);

    expect($payment->type)->toBe(TypeEnum::tinkoffQrCode);
    expect($payment->status)->toBe(StatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();

    expect($payment)->toBeStatus(StatusEnum::new);

    expect($payment->cashbox->info->extra['url'])->toBeUrl();

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);

    Event::assertNotDispatched(PaymentSuccessEvent::class);
    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentRefundedEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);
});

it('checks the success', function () {
    fakeEvents();
    fakeTinkoffQrCodeHttp();

    $payment = createPayment(TypeEnum::tinkoffQrCode);

    expect($payment->type)->toBe(TypeEnum::tinkoffQrCode);
    expect($payment->status)->toBe(StatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();

    expect($payment)->toBeStatus(StatusEnum::success);

    expect($payment->cashbox->info->extra['url'])->toBeUrl();

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);

    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 1);
    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentRefundedEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);
});

it('checks for invalid parameters being passed', function () {
    fakeEvents();
    fakeTinkoffQrCodeInvalidHttp();

    createPayment(TypeEnum::tinkoffQrCode);
})->expectException(BadRequestHttpException::class);
