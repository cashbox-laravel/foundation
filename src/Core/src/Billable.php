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

namespace Cashbox\Core;

use Cashbox\Core\Concerns\Config\Payment\Attributes;
use Cashbox\Core\Concerns\Helpers\Jobs;
use Cashbox\Core\Concerns\Repositories\Registry;
use Cashbox\Core\Models\Details;
use Cashbox\Core\Services\Driver;
use Cashbox\Core\Services\DriverManager;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Relations\Relation;

/**
 * @property Details $cashbox
 */
trait Billable
{
    use Attributes;
    use Jobs;
    use Registry;

    protected ?Driver $cashboxDriver = null;

    public function cashbox(): Relation
    {
        return $this->hasOne(Details::class, 'payment_id', $this->getKeyName());
    }

    public function cashboxDriver(): Driver
    {
        if ($this->cashboxDriver) {
            return $this->cashboxDriver;
        }

        return $this->cashboxDriver = DriverManager::find($this);
    }

    public function cashboxJob(bool $force = false): Services\Job
    {
        return static::job($this, $force);
    }

    public function cashboxAttributeType(): mixed
    {
        return $this->getAttribute(
            static::attribute()->type
        );
    }

    public function cashboxAttributeStatus(): mixed
    {
        return $this->getAttribute(
            static::attribute()->status
        );
    }

    public function cashboxAttributeCreatedAt(): DateTimeInterface
    {
        return $this->getAttribute(
            static::attribute()->createdAt
        );
    }
}
