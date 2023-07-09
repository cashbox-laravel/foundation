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

namespace CashierProvider\Core\Events\Http;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Queue\SerializesModels;
use Throwable;

class ExceptionEvent
{
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * The exception instance.
     *
     * @var Throwable|null
     */
    public $exception;

    /**
     * Create a new event instance.
     */
    public function __construct(?Throwable $exception = null)
    {
        $this->exception = $exception;
    }
}
