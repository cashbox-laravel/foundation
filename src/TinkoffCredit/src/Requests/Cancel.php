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

namespace CashierProvider\Tinkoff\Credit\Requests;

class Cancel extends BaseRequest
{
    protected $path = '/api/partners/v2/orders/{orderNumber}/cancel';

    protected $reload_relations = true;

    public function getRawBody(): array
    {
        return [];
    }
}
