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

use Cashbox\Core\Data\Signing\Token;

class BasicToken extends Base
{
    public static function get(string $clientId, string $clientSecret, array $data): Token
    {
        return static::token($clientId, $clientSecret);
    }
}
