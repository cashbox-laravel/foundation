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

namespace Tests\Responses;

use CashierProvider\Tinkoff\QrCode\Responses\QrCode;
use DragonCode\Contracts\Cashier\Http\Response;
use Tests\TestCase;

class QrCodeTest extends TestCase
{
    public function testInstance()
    {
        $response = $this->response();

        $this->assertInstanceOf(QrCode::class, $response);
        $this->assertInstanceOf(Response::class, $response);
    }

    public function testGetExternalId()
    {
        $response = $this->response();

        $this->assertSame(self::PAYMENT_EXTERNAL_ID, $response->getExternalId());
    }

    public function testGetStatus()
    {
        $response = $this->response();

        $response->put('Status', self::STATUS);

        $this->assertSame(self::STATUS, $response->getStatus());
    }

    public function testGetUrl()
    {
        $response = $this->response();

        $this->assertSame(self::URL, $response->getUrl());
    }

    public function testToArray()
    {
        $response = $this->response();

        $response->put('Status', self::STATUS);

        $this->assertSame([
            QrCode::KEY_STATUS => self::STATUS,
            QrCode::KEY_URL    => self::URL,
        ], $response->toArray());
    }

    /**
     * @return QrCode|Response
     */
    protected function response(): Response
    {
        return QrCode::make([
            'TerminalKey' => $this->getTerminalKey(),

            'OrderId'   => self::PAYMENT_ID,
            'Success'   => true,
            'Data'      => self::URL,
            'PaymentId' => self::PAYMENT_EXTERNAL_ID,
            'ErrorCode' => 0,
        ]);
    }
}
