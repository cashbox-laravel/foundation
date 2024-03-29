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

declare(strict_types=1);

namespace CashierProvider\Tinkoff\Auth;

use CashierProvider\Tinkoff\Auth\Constants\Keys;
use CashierProvider\Tinkoff\Auth\Resources\AccessToken;
use CashierProvider\Tinkoff\Auth\Support\Hash;
use DragonCode\Contracts\Cashier\Auth\Auth as AuthContract;
use DragonCode\Contracts\Cashier\Http\Request;
use DragonCode\Contracts\Cashier\Resources\Model;
use DragonCode\Support\Concerns\Makeable;

/** @method static Auth make(Model $model, Request $request, bool $hash = true) */
class Auth implements AuthContract
{
    use Makeable;

    /** @var Model */
    protected $model;

    /** @var Request */
    protected $request;

    /** @var bool */
    protected $hash;

    public function __construct(Model $model, Request $request, bool $hash = true, array $extra = [])
    {
        $this->model   = $model;
        $this->request = $request;
        $this->hash    = $hash;
    }

    public function headers(): array
    {
        return $this->request->getRawHeaders();
    }

    public function body(): array
    {
        $token = $this->getAccessToken();

        return array_merge($this->request->getRawBody(), [
            Keys::TERMINAL => $token->getClientId(),
            Keys::TOKEN    => $token->getAccessToken(),
        ]);
    }

    public function refresh(): void {}

    protected function getAccessToken(): AccessToken
    {
        return Hash::make()->get(
            $this->model,
            $this->request->getRawBody(),
            $this->hash
        );
    }
}
