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

namespace Tests\Auth;

use Helldar\CashierDriver\Tinkoff\Auth\Auth;
use Tests\TestCase;

class HeadersTest extends TestCase
{
    public function testBasic()
    {
        $auth = Auth::make($this->model(), $this->request(), false);

        $this->assertIsArray($auth->headers());

        $this->assertSame(['Accept' => 'application/json'], $auth->headers());
    }

    public function testHash()
    {
        $auth = Auth::make($this->model(), $this->request());

        $this->assertIsArray($auth->headers());

        $this->assertSame(['Accept' => 'application/json'], $auth->headers());
    }
}
