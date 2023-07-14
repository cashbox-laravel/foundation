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

namespace Cashbox\Core\Concerns\Config\Payment;

use Cashbox\Core\Concerns\Transformers\EnumsTransformer;
use Cashbox\Core\Data\Config\DriverData;
use Cashbox\Core\Exceptions\Internal\UnknownDriverConfigException;
use Cashbox\Core\Facades\Config;
use Illuminate\Database\Eloquent\Model;

trait Drivers
{
    use Attributes;
    use EnumsTransformer;

    protected static function drivers(): array
    {
        return Config::payment()->drivers;
    }

    protected static function driver(int|string $name, Model $payment): DriverData
    {
        if ($driver = Config::driver($name)) {
            return $driver;
        }

        throw new UnknownDriverConfigException($name, $payment->getKey());
    }

    protected static function driverByModel(Model $payment): DriverData
    {
        $name = $payment->getAttribute(
            static::attribute()->type
        );

        return static::driver(static::transformFromEnum($name), $payment);
    }
}
