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
 * @see https://github.com/cashbox-laravel/foundation
 */

namespace Tests\Observers;

use CashierProvider\Core\Constants\Status;
use CashierProvider\Core\Facades\Config\Payment as PaymentConfig;
use CashierProvider\Core\Providers\ObserverServiceProvider;
use DragonCode\Support\Facades\Http\Url;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\Concerns\TestServiceProvider;
use Tests\Fixtures\Models\RequestPayment;
use Tests\TestCase;

class ObserverTest extends TestCase
{
    use RefreshDatabase;

    protected $model = RequestPayment::class;

    protected function getPackageProviders($app): array
    {
        return [
            TestServiceProvider::class,
            ObserverServiceProvider::class,
        ];
    }

    public function testCreate(): Model
    {
        $this->assertSame(0, DB::table('payments')->count());
        $this->assertSame(0, DB::table('cashier_details')->count());

        $payment = $this->payment()->refresh();

        $this->assertSame(1, DB::table('payments')->count());
        $this->assertSame(1, DB::table('cashier_details')->count());

        $this->assertIsString($payment->cashier->external_id);
        $this->assertMatchesRegularExpression('/^demo-\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b$/', $payment->cashier->external_id);

        $this->assertTrue(Url::is($payment->cashier->details->getUrl()));

        $this->assertSame('NEW', $payment->cashier->details->getStatus());

        $this->assertSame(
            PaymentConfig::getStatuses()->getStatus(Status::NEW),
            $payment->status_id
        );

        return $payment;
    }

    public function testUpdate()
    {
        $payment = $this->testCreate();

        $payment->update([
            'sum' => 34.56,
        ]);

        $payment->refresh();

        $this->assertSame(1, DB::table('payments')->count());
        $this->assertSame(1, DB::table('cashier_details')->count());

        $this->assertIsString($payment->cashier->external_id);
        $this->assertMatchesRegularExpression('/^demo-\b[0-9a-f]{8}\b-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-\b[0-9a-f]{12}\b$/', $payment->cashier->external_id);

        $this->assertTrue(Url::is($payment->cashier->details->getUrl()));

        $this->assertSame('NEW', $payment->cashier->details->getStatus());

        $this->assertSame(
            PaymentConfig::getStatuses()->getStatus(Status::NEW),
            $payment->status_id
        );
    }
}
