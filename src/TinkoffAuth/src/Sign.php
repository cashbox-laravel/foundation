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
 * @see https://cashbox.city
 */

declare(strict_types=1);

namespace Cashbox\Tinkoff\Auth;

use Cashbox\Core\Data\Config\Drivers\CredentialsData;
use Cashbox\Core\Data\Signing\Token;
use Cashbox\Core\Services\Sign as BaseSign;
use Cashbox\Tinkoff\Auth\Constants\Keys;
use Cashbox\Tinkoff\Auth\Services\Hash;

class Sign extends BaseSign
{
    public function body(): array
    {
        $token = $this->hash();

        return array_merge($this->request->body(), [
            Keys::TERMINAL => $token->clientId,
            Keys::TOKEN    => $token->clientSecret,
        ]);
    }

    protected function hash(): Token
    {
        return Hash::get(
            $this->clientId(),
            $this->clientSecret(),
            $this->request->body(),
            $this->secure
        );
    }

    protected function clientId(): string
    {
        return $this->credentials()->clientId;
    }

    protected function clientSecret(): string
    {
        return $this->credentials()->clientSecret;
    }

    protected function credentials(): CredentialsData
    {
        return $this->request->resource->payment->cashboxDriver()->config->credentials;
    }
}
