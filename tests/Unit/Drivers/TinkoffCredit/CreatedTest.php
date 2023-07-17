<?php

declare(strict_types=1);

use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Http;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('checks the create', function () {
    fakeEvents();

    fakeHttpTinkoffCreditCreate();
    fakeHttpTinkoffCreditInfo();

    $payment = createPayment(TypeEnum::tinkoffCredit);

    expect($payment->type)->toBe(TypeEnum::tinkoffCredit);
    expect($payment->status)->toBe(StatusEnum::new);

    assertHasCashbox($payment);

    $payment->refresh();

    expect(StatusEnum::new)->toBe(
        $payment->cashboxDriver()->statuses()->detect($payment->cashbox->info->status)
    );

    expect($payment->cashbox->info->externalId)->toBeString()->toMatch(
        '/^demo-\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b$/'
    );

    assertIsUrl($payment->cashbox->info->extra['url']);

    Event::assertDispatchedTimes(CreatedEvent::class);

    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertNotDispatched(FailedEvent::class);
    Event::assertNotDispatched(RefundedEvent::class);
    Event::assertNotDispatched(WaitRefundEvent::class);

    Http::assertNothingSent();
});
