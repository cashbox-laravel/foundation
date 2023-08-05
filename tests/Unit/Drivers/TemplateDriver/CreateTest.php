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

use Cashbox\Core\Enums\CurrencyEnum;
use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentFailedEvent;
use Cashbox\Core\Events\PaymentRefundedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Cashbox\Core\Events\PaymentWaitRefundEvent;
use Cashbox\Core\Exceptions\External\BadRequestHttpException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('request data', function () {
    fakeEvents();
    fakeTemplateHttp();

    $payment = createPayment(TypeEnum::templateDriver, 1234);

    $data = [
        'OrderId'  => $payment->id,
        'Amount'   => $payment->price,
        'Currency' => CurrencyEnum::USD->value,
    ];

    // Init
    Http::assertSent(function (Request $request) use ($data) {
        return $request->hasHeader('X-Some-ID', 12345)
            && $request->data() === $data;
    });

    // Verify
    Http::assertSent(function (Request $request) {
        return $request->hasHeader('X-Some-ID', 12345);
    });
});

it('new payment', function () {
    fakeEvents();
    fakeTemplateHttp('NEW');

    $payment = createPayment(TypeEnum::templateDriver);

    expect($payment->type)->toBe(TypeEnum::templateDriver);
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

it('confirmed payment', function () {
    fakeEvents();
    fakeTemplateHttp();

    $payment = createPayment(TypeEnum::templateDriver);

    expect($payment->type)->toBe(TypeEnum::templateDriver);
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

it('unauthorized request', function () {
    fakeEvents();
    fakeTemplateInvalidHttp();

    createPayment(TypeEnum::templateDriver);
})->expectException(BadRequestHttpException::class);
