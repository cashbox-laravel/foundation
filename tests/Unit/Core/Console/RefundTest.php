<?php

declare(strict_types=1);

use Cashbox\Core\Console\Commands\Refund;
use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Illuminate\Support\Facades\Event;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

it('full refund', function () {
    fakeEvents();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::outside);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::cash);
    $payment5 = createPayment(TypeEnum::cash);
    $payment6 = createPayment(TypeEnum::cash);

    Event::assertDispatchedTimes(CreatedEvent::class, 4);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    fakeEvents();

    artisan(Refund::class);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::success);
    expect($payment4->refresh()->status)->toBe(StatusEnum::success);
    expect($payment5->refresh()->status)->toBe(StatusEnum::success);
    expect($payment6->refresh()->status)->toBe(StatusEnum::success);

    $payment1->refresh()->created_at = now()->subHour();
    $payment2->refresh()->created_at = now()->subHour();
    $payment3->refresh()->created_at = now()->subHour();
    $payment4->refresh()->created_at = now()->subHour();
    $payment5->refresh()->created_at = now()->subHour();
    $payment6->refresh()->created_at = now()->subHour();

    $payment1->save();
    $payment2->save();
    $payment3->save();
    $payment4->save();
    $payment5->save();
    $payment6->save();

    artisan(Refund::class);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment4->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment5->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment6->refresh()->status)->toBe(StatusEnum::refund);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertDispatchedTimes(RefundedEvent::class, 4);
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

    $payment3->refresh()->created_at = now()->subHour();
    $payment4->refresh()->created_at = now()->subHour();

    $payment3->save();
    $payment4->save();

    $payment5->refresh()->updateQuietly(['status' => StatusEnum::refund]);
    $payment6->refresh()->updateQuietly(['status' => StatusEnum::refund]);

    fakeEvents();

    artisan(Refund::class);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment4->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment5->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment6->refresh()->status)->toBe(StatusEnum::refund);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertDispatchedTimes(RefundedEvent::class, 2);
});

it('refund by ID', function () {
    fakeEvents();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::outside);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::cash);
    $payment5 = createPayment(TypeEnum::cash);
    $payment6 = createPayment(TypeEnum::cash);

    Event::assertDispatchedTimes(CreatedEvent::class, 4);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    fakeEvents();

    artisan(Refund::class, [
        'payment' => $payment4->id,
    ]);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertDispatchedTimes(RefundedEvent::class);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::success);
    expect($payment4->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment5->refresh()->status)->toBe(StatusEnum::success);
    expect($payment6->refresh()->status)->toBe(StatusEnum::success);
});

it('with force option', function () {
    fakeEvents();

    $payment1 = createPayment(TypeEnum::outside);
    $payment2 = createPayment(TypeEnum::outside);
    $payment3 = createPayment(TypeEnum::cash);
    $payment4 = createPayment(TypeEnum::cash);
    $payment5 = createPayment(TypeEnum::cash);
    $payment6 = createPayment(TypeEnum::cash);

    Event::assertDispatchedTimes(CreatedEvent::class, 4);
    Event::assertDispatchedTimes(SuccessEvent::class, 4);

    fakeEvents();

    artisan(Refund::class, [
        '--force' => true,
    ]);

    expect($payment1->refresh()->status)->toBe(StatusEnum::new);
    expect($payment2->refresh()->status)->toBe(StatusEnum::new);
    expect($payment3->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment4->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment5->refresh()->status)->toBe(StatusEnum::refund);
    expect($payment6->refresh()->status)->toBe(StatusEnum::refund);

    Event::assertNotDispatched(CreatedEvent::class);
    Event::assertNotDispatched(SuccessEvent::class);
    Event::assertDispatchedTimes(RefundedEvent::class, 4);
});
