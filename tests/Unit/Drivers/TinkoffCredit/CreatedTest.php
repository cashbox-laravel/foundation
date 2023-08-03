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

use Cashbox\Core\Enums\StatusEnum;
use Cashbox\Core\Events\PaymentCreatedEvent;
use Cashbox\Core\Events\PaymentFailedEvent;
use Cashbox\Core\Events\PaymentRefundedEvent;
use Cashbox\Core\Events\PaymentSuccessEvent;
use Cashbox\Core\Events\PaymentWaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum as AppStatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the progress', function () {
    fakeEvents();
    fakeTinkoffCreditHttp('new');

    $payment = createPayment(TypeEnum::tinkoffCredit);

    expect($payment->type)->toBe(TypeEnum::tinkoffCredit);
    expect($payment->status)->toBe(AppStatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();

    expect(StatusEnum::new)->toBe(
        $payment->cashboxDriver()->statuses()->detect($payment->cashbox->info->status)
    );

    expect($payment->cashbox->info->extra['url'])->toBeUrl();

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);

    Event::assertNotDispatched(PaymentSuccessEvent::class);
    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentRefundedEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);
});

it('checks the success', function () {
    fakeEvents();
    fakeTinkoffCreditHttp();

    $payment = createPayment(TypeEnum::tinkoffCredit);

    expect($payment->type)->toBe(TypeEnum::tinkoffCredit);
    expect($payment->status)->toBe(AppStatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();

    expect(StatusEnum::success)->toBe(
        $payment->cashboxDriver()->statuses()->detect($payment->cashbox->info->status)
    );

    expect($payment->cashbox->info->extra['url'])->toBeUrl();

    Event::assertDispatchedTimes(PaymentCreatedEvent::class);

    Event::assertDispatchedTimes(PaymentSuccessEvent::class, 1);
    Event::assertNotDispatched(PaymentFailedEvent::class);
    Event::assertNotDispatched(PaymentRefundedEvent::class);
    Event::assertNotDispatched(PaymentWaitRefundEvent::class);
});
