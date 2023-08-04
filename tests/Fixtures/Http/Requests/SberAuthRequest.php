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

namespace Tests\Fixtures\Http\Requests;

use Cashbox\Core\Http\Request;

class SberAuthRequest extends Request
{
    protected string $productionHost = 'https://example.com';

    protected string $productionUri = 'ru/prod/tokens/v2/oauth';

    public function body(): array
    {
        return [
            'OrderId' => '1234567890',
            'Amount'  => 1000,
        ];
    }
}
