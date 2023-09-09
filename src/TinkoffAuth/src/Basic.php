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

use Cashbox\Core\Data\Signing\Token;
use Cashbox\Core\Services\Auth as BaseSign;
use Cashbox\Tinkoff\Auth\Constants\Keys;
use Cashbox\Tinkoff\Auth\Tokens\BasicToken;

class Basic extends BaseSign
{
    public function body(): array
    {
        $token = $this->token();

        return array_merge($this->request->body(), [
            Keys::TERMINAL => $token->clientId,
            Keys::TOKEN    => $token->clientSecret,
        ]);
    }

    protected function token(): Token
    {
        return BasicToken::get($this->clientId(), $this->clientSecret(), $this->request->body());
    }

    protected function clientId(): string
    {
        return $this->config->credentials->clientId;
    }

    protected function clientSecret(): string
    {
        return $this->config->credentials->clientSecret;
    }
}
