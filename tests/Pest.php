<?php

use Cashbox\Core\Data\Config\ConfigData;
use Cashbox\Core\Events\CreatedEvent;
use Cashbox\Core\Events\FailedEvent;
use Cashbox\Core\Events\RefundedEvent;
use Cashbox\Core\Events\SuccessEvent;
use Cashbox\Core\Events\WaitRefundEvent;
use Cashbox\Core\Facades\Config;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Facade;
use Illuminate\Support\Facades\Http;
use Spatie\LaravelData\Casts\Cast;
use Spatie\LaravelData\Support\DataProperty;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;
use Tests\Fixtures\Data\FakeData;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

uses(TestCase::class)->in('Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

function assertHasCashbox(PaymentModel $payment): void
{
    expect(true)->toBeTrue(
        $payment->cashbox()->exists()
    );
}

function assertDoesntHaveCashbox(PaymentModel $payment): void
{
    expect(true)->toBeTrue(
        $payment->cashbox()->doesntExist()
    );
}

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function artisan(string $command, array $parameters = []): void
{
    Artisan::call($command, $parameters);
}

function forget(string $class, Facade|string|null $facade = null): void
{
    if ($facade) {
        $facade::clearResolvedInstances();
    }

    App::forgetInstance($class);
}

function forgetConfig(): void
{
    forget(ConfigData::class, Config::class);
}

function createPayment(TypeEnum $type, ?int $price = null): PaymentModel
{
    $price ??= random_int(1, 50000);

    return PaymentModel::create(compact('type', 'price'));
}

function fakeDataProperty(): DataProperty
{
    $reflection = new ReflectionProperty(FakeData::class, 'foo');

    return DataProperty::create($reflection);
}

function dataCast(Cast|string $cast, mixed $value, ?DataProperty $property = null, array $context = []): mixed
{
    $property ??= fakeDataProperty();

    $cast = is_string($cast) ? new $cast() : $cast;

    return $cast->cast($property, $value, $context);
}

function fakes(bool $events = true, bool $http = true): void
{
    if ($events) {
        Event::fake([
            CreatedEvent::class,
            FailedEvent::class,
            RefundedEvent::class,
            SuccessEvent::class,
            WaitRefundEvent::class,
        ]);
    }

    if ($http) {
        Http::fake();
    }
}
