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

namespace Cashbox\BankName\Technology\Http\Requests;

class CreateRequest extends BaseRequest
{
    protected string $productionUri = '/v1/init';

    protected bool $secure = false;

    public function body(): array
    {
        return [
            'OrderId'  => $this->resource->paymentId(),
            'Amount'   => $this->resource->sum(),
            'Currency' => $this->resource->currency(),
        ];
    }

    public function headers(): array
    {
        return [
            'X-Some-ID' => $this->resource->someIdentifier(),
        ];
    }
}
