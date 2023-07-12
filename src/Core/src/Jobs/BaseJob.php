<?php

/**
 * This file is part of the "cashier-provider/foundation" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@dragon-code.pro>
 * @copyright 2023 Andrey Helldar
 * @license MIT
 *
 * @see https://github.com/cashier-provider/foundation
 */

declare(strict_types=1);

namespace CashierProvider\Core\Jobs;

use CashierProvider\Core\Billable;
use CashierProvider\Core\Concerns\Config\Queue;
use CashierProvider\Core\Data\Http\Response;
use CashierProvider\Core\Enums\RateLimiterEnum;
use CashierProvider\Core\Exceptions\External\EmptyResponseException;
use CashierProvider\Core\Services\Driver;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\Middleware\RateLimitedWithRedis;
use Illuminate\Queue\SerializesModels;

/**
 * @property Model|Billable $payment
 */
abstract class BaseJob implements ShouldBeUnique, ShouldQueue
{
    use InteractsWithQueue;
    use Queue;
    use Queueable;
    use SerializesModels;

    public int $tries = 10;

    public int $maxExceptions = 3;

    abstract protected function action(): Response;

    public function __construct(
        public Model $payment,
        public bool $force = false
    ) {
        $this->tries         = static::queue()->tries;
        $this->maxExceptions = static::queue()->exceptions;
    }

    public function handle(): void
    {
        retry($this->maxExceptions, fn () => $this->store($this->action()));
    }

    public function uniqueId(): int
    {
        return $this->payment->getKey();
    }

    public function middleware(): array
    {
        return [new RateLimitedWithRedis($this->getRateLimiter())];
    }

    protected function store(Response $response, bool $replaceInfo = false): void
    {
        if ($response->isEmpty()) {
            $this->fail(new EmptyResponseException());
        }

        $data = [
            'external_id'  => $response->getExternalId(),
            'operation_id' => $response->getOperationId(),
            'info'         => $response->toArray(),
        ];

        if (! $replaceInfo) {
            $stored = $this->payment->cashier?->info?->toArray() ?? [];

            $data['info'] = array_merge($stored, $data['info']);
        }

        $this->payment->cashier
            ? $this->payment->cashier->update($data)
            : $this->payment->cashier()->create($data);
    }

    protected function driver(): Driver
    {
        return $this->payment->cashierDriver();
    }

    protected function getRateLimiter(): string
    {
        return $this->force ? RateLimiterEnum::disabled() : RateLimiterEnum::enabled();
    }
}
