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

namespace Tests;

use CashierProvider\BankName\Technology\Driver as Technology;
use CashierProvider\Core\Http\ResponseInfo;
use CashierProvider\Core\Services\Jobs;
use DragonCode\Contracts\Cashier\Driver as DriverContract;
use DragonCode\Contracts\Cashier\Http\Response as ResponseContract;
use DragonCode\Support\Facades\Http\Url;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\Models\RequestPayment;

class DriverTest extends TestCase
{
    protected $model = RequestPayment::class;

    public function testStart()
    {
        $payment = $this->payment();

        $response = $this->driver($payment)->start();

        $this->assertInstanceOf(ResponseInfo::class, $response);
        $this->assertInstanceOf(ResponseContract::class, $response);

        $this->assertIsString($response->getExternalId());
        $this->assertMatchesRegularExpression('/^(\d+)$/', $response->getExternalId());

        $this->assertNull($response->getStatus());

        $this->assertTrue(Url::is($response->getUrl()));
    }

    public function testCheck()
    {
        $payment = $this->payment();

        Jobs::make($payment)->start();

        $response = $this->driver($payment)->check();

        $this->assertInstanceOf(ResponseInfo::class, $response);
        $this->assertInstanceOf(ResponseContract::class, $response);

        $this->assertIsString($response->getExternalId());
        $this->assertMatchesRegularExpression('/^(\d+)$/', $response->getExternalId());

        $this->assertSame('FORM_SHOWED', $response->getStatus());

        $this->assertSame([
            'status' => 'FORM_SHOWED',
        ], $response->toArray());
    }

    public function testRefund()
    {
        $payment = $this->payment();

        Jobs::make($payment)->start();
        Jobs::make($payment)->check(true);

        $response = $this->driver($payment)->refund();

        $this->assertInstanceOf(ResponseInfo::class, $response);
        $this->assertInstanceOf(ResponseContract::class, $response);

        $this->assertIsString($response->getExternalId());
        $this->assertMatchesRegularExpression('/^(\d+)$/', $response->getExternalId());

        $this->assertSame('CANCELED', $response->getStatus());
    }

    protected function driver(Model $payment): DriverContract
    {
        $config = $this->config();

        return Technology::make($config, $payment);
    }
}
