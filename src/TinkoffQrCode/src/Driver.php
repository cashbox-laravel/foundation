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

namespace Cashbox\Tinkoff\QrCode;

use Cashbox\Core\Http\Response as BaseResponse;
use Cashbox\Core\Services\Driver as BaseDriver;
use Cashbox\Tinkoff\QrCode\Http\Requests\CancelRequest;
use Cashbox\Tinkoff\QrCode\Http\Requests\CreateRequest;
use Cashbox\Tinkoff\QrCode\Http\Requests\GetQrRequest;
use Cashbox\Tinkoff\QrCode\Http\Requests\GetStateRequest;
use Cashbox\Tinkoff\QrCode\Http\Responses\Response;
use Cashbox\Tinkoff\QrCode\Services\Exception;
use Cashbox\Tinkoff\QrCode\Services\Statuses;

/**
 * @see https://www.tinkoff.ru/kassa/develop/api/payments/
 */
class Driver extends BaseDriver
{
    protected string $statuses = Statuses::class;

    protected string $exception = Exception::class;

    protected string $response = Response::class;

    public function start(): BaseResponse
    {
        $response = $this->request(CreateRequest::class);

        return $this->request(GetQrRequest::class, prev: $response);
    }

    public function verify(): BaseResponse
    {
        return $this->request(GetStateRequest::class);
    }

    public function refund(): BaseResponse
    {
        return $this->request(CancelRequest::class);
    }
}
