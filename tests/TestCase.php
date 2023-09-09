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

namespace Tests;

use Cashbox\BankName\Technology\Driver as TemplateDriver;
use Cashbox\Cash\Driver as CashDriver;
use Cashbox\Core\Enums\AttributeEnum;
use Cashbox\Core\Enums\StatusEnum;
use Cashbox\Core\Providers\BindingServiceProvider;
use Cashbox\Core\Providers\ObserverServiceProvider;
use Cashbox\Core\Providers\RateLimiterServiceProvider;
use Cashbox\Core\Providers\ServiceProvider;
use Cashbox\Tinkoff\Credit\Driver as TinkoffCreditDriver;
use Cashbox\Tinkoff\Online\Driver as TinkoffOnlineDriver;
use Cashbox\Tinkoff\QrCode\Driver as TinkoffQrCodeDriver;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\TestCase as BaseTestCase;
use Spatie\LaravelData\LaravelDataServiceProvider;
use Tests\Fixtures\App\Enums\StatusEnum as TestStatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;
use Tests\Fixtures\App\Providers\TestServiceProvider;
use Tests\Fixtures\Payments\Cash;
use Tests\Fixtures\Payments\TemplateDriver as TemplateDriverResource;
use Tests\Fixtures\Payments\TinkoffCredit;
use Tests\Fixtures\Payments\TinkoffOnline;
use Tests\Fixtures\Payments\TinkoffQrCode;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app): array
    {
        return [
            LaravelDataServiceProvider::class,
            ServiceProvider::class,
            BindingServiceProvider::class,
            ObserverServiceProvider::class,
            RateLimiterServiceProvider::class,
            TestServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app): void
    {
        $this->setUpPaymentModel($app);
        $this->setUpAttributes($app);
        $this->setUpStatuses($app);
        $this->setUpDrivers($app);
    }

    protected function defineDatabaseMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/database');
    }

    protected function setUpPaymentModel(Application $app): void
    {
        $app['config']->set('cashbox.payment.model', PaymentModel::class);
    }

    protected function setUpAttributes(Application $app): void
    {
        $app['config']->set('cashbox.payment.attribute.' . AttributeEnum::type(), 'type');
        $app['config']->set('cashbox.payment.attribute.' . AttributeEnum::status(), 'status');
    }

    protected function setUpStatuses(Application $app): void
    {
        $app['config']->set('cashbox.payment.status.' . StatusEnum::new(), TestStatusEnum::new);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::success(), TestStatusEnum::success);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::waitRefund(), TestStatusEnum::waitRefund);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::refund(), TestStatusEnum::refund);
        $app['config']->set('cashbox.payment.status.' . StatusEnum::failed(), TestStatusEnum::failed);
    }

    protected function setUpDrivers(Application $app): void
    {
        $this->setUpDriver($app, TypeEnum::cash, CashDriver::class, Cash::class);

        $this->setUpDriver($app, TypeEnum::tinkoffOnline, TinkoffOnlineDriver::class, TinkoffOnline::class);
        $this->setUpDriver($app, TypeEnum::tinkoffQrCode, TinkoffQrCodeDriver::class, TinkoffQrCode::class);
        $this->setUpDriver($app, TypeEnum::tinkoffCredit, TinkoffCreditDriver::class, TinkoffCredit::class, [
            'showcase_id' => fake()->randomLetter,
            'promo_code'  => 'default',
        ]);

        $this->setUpDriver($app, TypeEnum::sberQrCode, TinkoffQrCodeDriver::class, TinkoffQrCode::class);

        $this->setUpDriver($app, TypeEnum::templateDriver, TemplateDriver::class, TemplateDriverResource::class, [
            'extra' => ['some_id' => 12345],
        ]);
    }

    protected function setUpDriver(
        Application $app,
        TypeEnum $type,
        string $driver,
        string $resource,
        array $credentials = []
    ): void {
        $app['config']->set('cashbox.payment.drivers.' . $type->value, $type);

        $app['config']->set('cashbox.drivers.' . $type->value, [
            'driver'      => $driver,
            'resource'    => $resource,
            'credentials' => array_merge([
                'client_id'     => 'qwerty',
                'client_secret' => 'qwerty123',
            ], $credentials),
        ]);
    }
}
