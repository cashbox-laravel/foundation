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

namespace CashierProvider\Tinkoff\Auth\Resources;

use CashierProvider\Tinkoff\Auth\Constants\Keys;
use DragonCode\Contracts\Cashier\Resources\AccessToken as AccessTokenContract;
use DragonCode\SimpleDataTransferObject\DataTransferObject;
use Illuminate\Support\Carbon;

class AccessToken extends DataTransferObject implements AccessTokenContract
{
    protected $client_id;

    protected $access_token;

    protected $map = [
        Keys::TERMINAL => 'client_id',
        Keys::TOKEN    => 'access_token',
    ];

    public function getClientId(): string
    {
        return $this->client_id;
    }

    public function getAccessToken(): string
    {
        return $this->access_token;
    }

    public function getExpiresIn(): Carbon
    {
        return Carbon::now()->addDay();
    }
}
