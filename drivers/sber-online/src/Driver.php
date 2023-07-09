<?php

/*
 * This file is part of the "cashier-provider/sber-online" project.
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
 * @see https://github.com/cashier-provider/sber-online
 */

namespace CashierProvider\Sber\Online;

use CashierProvider\Core\Services\Driver as BaseDriver;
use CashierProvider\Sber\Online\Exceptions\Manager;
use CashierProvider\Sber\Online\Helpers\Statuses;
use CashierProvider\Sber\Online\Requests\Cancel;
use CashierProvider\Sber\Online\Requests\Create;
use CashierProvider\Sber\Online\Requests\Status;
use CashierProvider\Sber\Online\Resources\Details;
use CashierProvider\Sber\Online\Responses\Cancel as CancelResponse;
use CashierProvider\Sber\Online\Responses\Online;
use CashierProvider\Sber\Online\Responses\Status as StatusResponse;
use DragonCode\Contracts\Cashier\Http\Response;

class Driver extends BaseDriver
{
    protected $exceptions = Manager::class;

    protected $statuses = Statuses::class;

    protected $details = Details::class;

    public function start(): Response
    {
        $request = Create::make($this->model);

        return $this->request($request, Online::class);
    }

    public function check(): Response
    {
        $request = Status::make($this->model);

        return $this->request($request, StatusResponse::class);
    }

    public function refund(): Response
    {
        $request = Cancel::make($this->model);

        return $this->request($request, CancelResponse::class);
    }
}
