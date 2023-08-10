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

namespace Cashbox\Tinkoff\Auth\Tokens;

use Carbon\Carbon;
use Cashbox\Core\Data\Signing\Token;
use DateTimeInterface;

abstract class Base
{
    abstract public static function get(string $clientId, string $clientSecret, array $data): Token;

    protected static function token(string $clientId, string $clientSecret, ?DateTimeInterface $expiresIn = null): Token
    {
        $expiresIn ??= Carbon::now()->addDay();

        return Token::from(compact('clientId', 'clientSecret', 'expiresIn'));
    }
}
