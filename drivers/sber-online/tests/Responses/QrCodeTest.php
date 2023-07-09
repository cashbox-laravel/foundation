<?php

/*
 * This file is part of the "cashier-provider/sber-online" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@ai-rus.com>
 *
 * @copyright 2021 Andrey Helldar
 *
 * @license MIT
 *
 * @see https://github.com/cashier-provider/sber-online
 */

namespace Tests\Responses;

use CashierProvider\Sber\Online\Responses\Online;
use DragonCode\Contracts\Cashier\Http\Response;
use Tests\TestCase;

class OnlineTest extends TestCase
{
    public function testInstance()
    {
        $response = $this->response();

        $this->assertInstanceOf(Online::class, $response);
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

        $this->assertSame([
            Online::KEY_STATUS => self::STATUS,
            Online::KEY_URL    => self::URL,
        ], $response->toArray());
    }

    /**
     * @return \CashierProvider\Sber\Online\Responses\Online|\DragonCode\Contracts\Cashier\Http\Response
     */
    protected function response(): Response
    {
        return Online::make([
            'status' => [
                'order_id'       => self::PAYMENT_EXTERNAL_ID,
                'order_state'    => self::STATUS,
                'order_form_url' => self::URL,
                'error'          => 0,
            ],
        ]);
    }
}
