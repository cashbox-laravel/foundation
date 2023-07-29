<?php

declare(strict_types=1);

use Cashbox\Core\Enums\StatusEnum;
use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum as AppStatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the create', function () {
    fakeEvents();

    httpTinkoffCreditCreate();
    httpTinkoffCreditInfo();

    $payment = createPayment(TypeEnum::tinkoffCredit);

    expect($payment->type)->toBe(TypeEnum::tinkoffCredit);
    expect($payment->status)->toBe(AppStatusEnum::new);

    expect($payment)->toBeHasCashbox();

    $payment->refresh();

    expect(StatusEnum::new)->toBe(
        $payment->cashboxDriver()->statuses()->detect($payment->cashbox->info->status)
    );

    expect($payment->cashbox->info->extra['url'])->toBeUrl();

    Event::assertDispatchedTimes(CreatedEvent::class);

    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);
});
