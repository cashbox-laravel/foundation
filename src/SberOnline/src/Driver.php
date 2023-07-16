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

namespace Cashbox\BankName\Technology;

use Cashbox\BankName\Technology\Exceptions\Manager;
use Cashbox\BankName\Technology\Helpers\Statuses;
use Cashbox\BankName\Technology\Requests\Cancel;
use Cashbox\BankName\Technology\Requests\GetState;
use Cashbox\BankName\Technology\Requests\Init;
use Cashbox\BankName\Technology\Resources\Details;
use Cashbox\BankName\Technology\Responses\Created;
use Cashbox\BankName\Technology\Responses\Refund;
use Cashbox\BankName\Technology\Responses\State;
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
