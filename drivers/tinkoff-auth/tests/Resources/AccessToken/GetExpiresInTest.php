<?php

/*
 * This file is part of the "andrey-helldar/cashier-tinkoff-auth" project.
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
 * @see https://github.com/andrey-helldar/cashier-tinkoff-auth
 */

declare(strict_types=1);

namespace Tests\Resources\AccessToken;

use Carbon\Carbon as BaseCarbon;
use DateTimeInterface;
use Helldar\CashierDriver\Tinkoff\Auth\Resources\AccessToken;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class GetExpiresInTest extends TestCase
{
    public function testBasic()
    {
        $token = AccessToken::make($this->credentials());

        $this->assertInstanceOf(Carbon::class, $token->getExpiresIn());
        $this->assertInstanceOf(BaseCarbon::class, $token->getExpiresIn());
        $this->assertInstanceOf(DateTimeInterface::class, $token->getExpiresIn());

        $this->assertGreaterThan(Carbon::now(), $token->getExpiresIn());
    }

    public function testHashed()
    {
        $token = AccessToken::make($this->credentialsHash());

        $this->assertInstanceOf(Carbon::class, $token->getExpiresIn());
        $this->assertInstanceOf(BaseCarbon::class, $token->getExpiresIn());
        $this->assertInstanceOf(DateTimeInterface::class, $token->getExpiresIn());

        $this->assertGreaterThan(Carbon::now(), $token->getExpiresIn());
    }
}
