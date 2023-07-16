<?php

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
            $this->hash
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
