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

use CashierProvider\Core\Http\ResponseInfo;
use CashierProvider\Tinkoff\Online\Driver as Online;
use DragonCode\Contracts\Cashier\Driver as DriverContract;
use DragonCode\Contracts\Cashier\Http\Response as ResponseContract;
use DragonCode\Support\Facades\Http\Url;
use Tests\Fixtures\Models\RequestPayment;

class DriverTest extends TestCase
{
    protected $model = RequestPayment::class;

    protected function setUp(): void
    {
        parent::setUp();

        $this->runSeeders();
    }

    public function testStart()
    {
        $response = $this->driver()->start();

        $this->assertInstanceOf(ResponseInfo::class, $response);
        $this->assertInstanceOf(ResponseContract::class, $response);

        $this->assertIsString($response->getExternalId());
        $this->assertMatchesRegularExpression('/^(\d+)$/', $response->getExternalId());

        $this->assertSame('NEW', $response->getStatus());

        $this->assertTrue(Url::is($response->getUrl()));
    }

    public function testCheck()
    {
        $this->driver()->start();

        $response = $this->driver()->check();

        $this->assertInstanceOf(ResponseInfo::class, $response);
        $this->assertInstanceOf(ResponseContract::class, $response);

        $this->assertIsString($response->getExternalId());
        $this->assertMatchesRegularExpression('/^(\d+)$/', $response->getExternalId());

        $this->assertSame('NEW', $response->getStatus());

        $this->assertSame([
            'status' => 'NEW',
        ], $response->toArray());
    }

    public function testRefund()
    {
        $this->driver()->start();

        $response = $this->driver()->refund();

        $this->assertInstanceOf(ResponseInfo::class, $response);
        $this->assertInstanceOf(ResponseContract::class, $response);

        $this->assertIsString($response->getExternalId());
        $this->assertMatchesRegularExpression('/^(\d+)$/', $response->getExternalId());

        $this->assertSame('CANCELED', $response->getStatus());
    }

    protected function driver(): DriverContract
    {
        $model = $this->payment();

        $config = $this->config();

        return Online::make($config, $model);
    }

    protected function payment(): RequestPayment
    {
        return RequestPayment::firstOrFail();
    }
}
