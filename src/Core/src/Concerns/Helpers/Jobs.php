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

namespace CashierProvider\Core\Concerns\Helpers;

use CashierProvider\Core\Services\Job;
use Illuminate\Database\Eloquent\Model;

trait Jobs
{
    protected static function job(Model $payment, bool $force = false): Job
    {
        return Job::model($payment)->force($force);
    }
}
