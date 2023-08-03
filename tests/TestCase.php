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
use Tests\Fixtures\Payments\Cash;
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
        $this->setUpCashDriver($app);
        $this->setUpTinkoffCreditDriver($app);
        $this->setUpTinkoffOnlineDriver($app);
        $this->setUpTinkoffQrCodeDriver($app);
    }

    protected function setUpCashDriver(Application $app): void
    {
        $app['config']->set('cashbox.payment.drivers.' . TypeEnum::cash(), TypeEnum::cash);

        $app['config']->set('cashbox.drivers.' . TypeEnum::cash(), [
            'driver'   => CashDriver::class,
            'resource' => Cash::class,
        ]);
    }

    protected function setUpTinkoffCreditDriver(Application $app): void
    {
        $app['config']->set('cashbox.payment.drivers.' . TypeEnum::tinkoffCredit(), TypeEnum::tinkoffCredit);

        $app['config']->set('cashbox.drivers.' . TypeEnum::tinkoffCredit(), [
            'driver'      => TinkoffCreditDriver::class,
            'resource'    => TinkoffCredit::class,
            'credentials' => [
                // shopId
                'client_id'     => fake()->randomLetter,

                // password
                'client_secret' => fake()->password,

                'showcase_id' => fake()->randomLetter,

                'promo_code' => 'default',
            ],
        ]);
    }

    protected function setUpTinkoffOnlineDriver(Application $app): void
    {
        $app['config']->set('cashbox.payment.drivers.' . TypeEnum::tinkoffOnline(), TypeEnum::tinkoffOnline);

        $app['config']->set('cashbox.drivers.' . TypeEnum::tinkoffOnline(), [
            'driver'      => TinkoffOnlineDriver::class,
            'resource'    => TinkoffOnline::class,
            'credentials' => [
                'client_id'     => fake()->randomLetter,
                'client_secret' => fake()->password,
            ],
        ]);
    }

    protected function setUpTinkoffQrCodeDriver(Application $app): void
    {
        $app['config']->set('cashbox.payment.drivers.' . TypeEnum::tinkoffQrCode(), TypeEnum::tinkoffQrCode);

        $app['config']->set('cashbox.drivers.' . TypeEnum::tinkoffQrCode(), [
            'driver'      => TinkoffQrCodeDriver::class,
            'resource'    => TinkoffQrCode::class,
            'credentials' => [
                'client_id'     => fake()->randomLetter,
                'client_secret' => fake()->password,
            ],
        ]);
    }
}
