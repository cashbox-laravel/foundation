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

namespace Tests\Resources;

use CashierProvider\Core\Resources\Details as BaseDetails;
use CashierProvider\Tinkoff\Credit\Resources\Details;
use DragonCode\Contracts\Cashier\Resources\Details as DetailsContract;
use Tests\TestCase;

class DetailsTest extends TestCase
{
    protected $expected = [
        'status' => self::STATUS,
        'url'    => self::URL,
    ];

    public function testMake()
    {
        $details = $this->details();

        $this->assertInstanceOf(Details::class, $details);
        $this->assertInstanceOf(BaseDetails::class, $details);
        $this->assertInstanceOf(DetailsContract::class, $details);
    }

    public function testGetUrl()
    {
        $details = $this->details();

        $this->assertSame(self::URL, $details->getUrl());
    }

    public function testGetStatus()
    {
        $details = $this->details();

        $this->assertSame(self::STATUS, $details->getStatus());
    }

    public function testToArray()
    {
        $details = $this->details();

        $this->assertSame($this->expected, $details->toArray());
    }

    public function testToJson()
    {
        $details = $this->details();

        $this->assertJson($details->toJson());

        $this->assertSame(json_encode($this->expected), $details->toJson());
    }

    protected function details(): Details
    {
        return Details::make($this->expected);
    }
}
