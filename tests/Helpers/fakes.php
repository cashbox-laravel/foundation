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
use Spatie\LaravelData\Support\DataProperty;
use Tests\Fixtures\Data\FakeData;

function fakeDataProperty(): DataProperty
{
    $reflection = new ReflectionProperty(FakeData::class, 'foo');

    return DataProperty::create($reflection);
}

function fakeEvents(): void
{
    Event::fake([
        PaymentCreatedEvent::class,
        PaymentFailedEvent::class,
        PaymentRefundedEvent::class,
        PaymentSuccessEvent::class,
        PaymentWaitRefundEvent::class,
    ]);
}