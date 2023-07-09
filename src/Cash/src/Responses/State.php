<?php

/*
 * This file is part of the "cashier-provider/cash" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@ai-rus.com>
 *
 * @copyright 2021 Andrey Helldar
 *
 * @license MIT
 *
 * @see https://github.com/cashier-provider/cash
 */

declare(strict_types=1);

namespace Cash\src\Responses;

use CashierProvider\Core\Http\Response;

class State extends Response
{
    protected $map = [
        self::KEY_EXTERNAL_ID => 'PaymentId',

        self::KEY_STATUS => 'Status',
    ];

    public function isEmpty(): bool
    {
        return empty($this->getExternalId()) || empty($this->getStatus());
    }
}