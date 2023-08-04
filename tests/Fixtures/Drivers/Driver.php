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

namespace Tests\Fixtures\Drivers;

use Cashbox\Core\Http\Response;
use Cashbox\Core\Services\Driver as BaseDriver;

class Driver extends BaseDriver
{
    protected string $statuses = 'foo';

    protected string $exception = 'foo';

    protected string $response = 'foo';

    public function refund(): Response {}

    public function start(): Response {}

    public function verify(): Response {}
}
