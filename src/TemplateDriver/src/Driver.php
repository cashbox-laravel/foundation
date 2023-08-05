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

namespace Cashbox\BankName\Technology;

use Cashbox\BankName\Technology\Http\Requests\CancelRequest;
use Cashbox\BankName\Technology\Http\Requests\CreateRequest;
use Cashbox\BankName\Technology\Http\Requests\GetStateRequest;
use Cashbox\BankName\Technology\Http\Responses\Response;
use Cashbox\BankName\Technology\Services\Exception;
use Cashbox\BankName\Technology\Services\Statuses;
use Cashbox\Core\Http\Response as BaseResponse;
use Cashbox\Core\Services\Driver as BaseDriver;

class Driver extends BaseDriver
{
    protected string $statuses = Statuses::class;

    protected string $exception = Exception::class;

    protected string $response = Response::class;

    public function start(): BaseResponse
    {
        return $this->request(CreateRequest::class);
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
