<?php

declare(strict_types=1);

use Cashbox\Core\Console\Commands\Verify;
use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('full verification', function () {
    fakeEvents();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::outside);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::cash);
    $payment5 = createPayment(TypeEnum::cash);
    $payment6 = createPayment(TypeEnum::cash);

    Event::assertDispatchedTimes(CreatedEvent::class, 4);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    $payment3->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment4->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment5->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment6->refresh()->updateQuietly(['status' => StatusEnum::new]);

    fakeEvents();

    artisan(Verify::class);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::success);
    expect($payment4->refresh()->status)->toBe(StatusEnum::success);
    expect($payment5->refresh()->status)->toBe(StatusEnum::success);
    expect($payment6->refresh()->status)->toBe(StatusEnum::success);
});

it('partial verification', function () {
    fakeEvents();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::outside);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::cash);
    $payment5 = createPayment(TypeEnum::cash);
    $payment6 = createPayment(TypeEnum::cash);

    Event::assertDispatchedTimes(CreatedEvent::class, 4);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    $payment5->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment6->refresh()->updateQuietly(['status' => StatusEnum::new]);

    fakeEvents();

    artisan(Verify::class);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertDispatchedTimes(SuccessEvent::class, 2);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::success);
    expect($payment4->refresh()->status)->toBe(StatusEnum::success);
    expect($payment5->refresh()->status)->toBe(StatusEnum::success);
    expect($payment6->refresh()->status)->toBe(StatusEnum::success);
});

it('verify by ID', function () {
    fakeEvents();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::outside);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::cash);
    $payment5 = createPayment(TypeEnum::cash);
    $payment6 = createPayment(TypeEnum::cash);

    Event::assertDispatchedTimes(CreatedEvent::class, 4);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    $payment3->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment4->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment5->refresh()->updateQuietly(['status' => StatusEnum::new]);
    $payment6->refresh()->updateQuietly(['status' => StatusEnum::new]);

    fakeEvents();

    artisan(Verify::class, [
        'payment' => $payment4->id,
    ]);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertDispatchedTimes(SuccessEvent::class);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::new);
    expect($payment4->refresh()->status)->toBe(StatusEnum::success);
    expect($payment5->refresh()->status)->toBe(StatusEnum::new);
    expect($payment6->refresh()->status)->toBe(StatusEnum::new);
});
