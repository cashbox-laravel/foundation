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
 * @see https://github.com/cashbox/foundation
 */

declare(strict_types=1);

namespace Tests\Resources\AccessToken;

use CashierProvider\Tinkoff\Auth\Resources\AccessToken;
use DragonCode\Contracts\Cashier\Resources\AccessToken as AccessTokenContract;
use Tests\TestCase;

class MakeTest extends TestCase
{
    public function testMake()
    {
        $token = AccessToken::make();

        $this->assertInstanceOf(AccessTokenContract::class, $token);
    }

    public function testConstruct()
    {
        $token = new AccessToken();

        $this->assertInstanceOf(AccessTokenContract::class, $token);
    }
}
