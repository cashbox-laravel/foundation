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

namespace Cashbox\Sber\Online;

use Cashbox\Sber\Online\Exceptions\Manager;
use Cashbox\Sber\Online\Helpers\Statuses;
use Cashbox\Sber\Online\Requests\Cancel;
use Cashbox\Sber\Online\Requests\GetState;
use Cashbox\Sber\Online\Requests\Init;
use Cashbox\Sber\Online\Resources\Details;
use Cashbox\Sber\Online\Responses\Created;
use Cashbox\Sber\Online\Responses\Refund;
use Cashbox\Sber\Online\Responses\State;
use Cashbox\Core\Services\Driver as BaseDriver;
use DragonCode\Contracts\Cashier\Http\Response;

class Driver extends BaseDriver
{
    protected $exception = Manager::class;

    protected $statuses = Statuses::class;

    protected $details = Details::class;

    public function start(): Response
    {
        $request = Init::make($this->model);

        return $this->request($request, Created::class);
    }

    public function check(): Response
    {
        $request = GetState::make($this->model);

        return $this->request($request, State::class);
    }

    public function refund(): Response
    {
        $request = Cancel::make($this->model);

        return $this->request($request, Refund::class);
    }
}
