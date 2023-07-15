<?php

namespace Tests;

use Cashbox\Cash\Driver;
use Cashbox\Core\Enums\AttributeEnum;
use Cashbox\Core\Enums\StatusEnum;
use Cashbox\Core\Providers\BindingServiceProvider;
use Cashbox\Core\Providers\ObserverServiceProvider;
use Cashbox\Core\Providers\RateLimiterServiceProvider;
use Cashbox\Core\Providers\ServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Tests\Fixtures\Details\CashPaymentDetails;
use Tests\Fixtures\Enums\StatusEnum as TestStatusEnum;
use Tests\Fixtures\Enums\TypeEnum;
use Tests\Fixtures\Models\PaymentModel;
use Tests\Fixtures\Providers\TestServiceProvider;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            TestServiceProvider::class,
            ServiceProvider::class,
            BindingServiceProvider::class,
            ObserverServiceProvider::class,
            RateLimiterServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $app['config']->set('cashbox.payment.model', PaymentModel::class);

        $app['config']->set('cashbox.payment.attribute.' . AttributeEnum::type(), 'type');
        $app['config']->set('cashbox.payment.attribute.' . AttributeEnum::status(), 'status');

        $app['config']->set('cashbox.payment.status.' . StatusEnum::new(), TestStatusEnum::new);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::success(), TestStatusEnum::success);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::waitRefund(), TestStatusEnum::waitRefund);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::refund(), TestStatusEnum::refund);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::failed(), TestStatusEnum::failed);

        $app['config']->set('cashbox.payment.drivers.' . TypeEnum::cash(), TypeEnum::cash());

        $app['config']->set('cashbox.drivers.' . TypeEnum::cash(), [
            'driver'  => Driver::class,
            'details' => CashPaymentDetails::class,
        ]);
    }
}
