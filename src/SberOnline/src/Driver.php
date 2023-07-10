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

namespace SberOnline\src;

use CashierProvider\Core\Services\Driver as BaseDriver;
use SberOnline\src\Exceptions\Manager;
use SberOnline\src\Helpers\Statuses;
use SberOnline\src\Requests\Cancel;
use SberOnline\src\Requests\Create;
use SberOnline\src\Requests\Status;
use SberOnline\src\Resources\Details;
use SberOnline\src\Responses\Cancel as CancelResponse;
use SberOnline\src\Responses\Online;
use SberOnline\src\Responses\Status as StatusResponse;
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
