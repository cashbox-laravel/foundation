<?php

/*
 * This file is part of the "cashier-provider/core" project.
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
 * @see https://github.com/cashier-provider/core
 */

declare(strict_types=1);

namespace CashierProvider\Core\Constants;

class Status
{
    public const NEW         = 'new';
    public const SUCCESS     = 'success';
    public const FAILED      = 'failed';
    public const REFUND      = 'refund';
    public const WAIT_REFUND = 'wait_refund';
}
