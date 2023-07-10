<?php

/**
 * This file is part of the "cashier-provider/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://github.com/cashier-provider/foundation
 */

declare(strict_types=1);

namespace Tests\Resources\AccessToken;

use CashierProvider\Tinkoff\Auth\Resources\AccessToken;
use Tests\TestCase;

class GetAccessTokenTest extends TestCase
{
    public function testBasic()
    {
        $token = AccessToken::make($this->credentials());

        $this->assertSame(self::TOKEN, $token->getAccessToken());
    }

    public function testHashed()
    {
        $token = AccessToken::make($this->credentialsHash());

        $this->assertSame(self::TOKEN_HASH, $token->getAccessToken());
    }
}
