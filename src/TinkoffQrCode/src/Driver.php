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

namespace Cashbox\Tinkoff\QrCode;

use Cashbox\Core\Facades\Helpers\Model;
use Cashbox\Core\Services\Driver as BaseDriver;
use Cashbox\Tinkoff\QrCode\Exceptions\Manager;
use Cashbox\Tinkoff\QrCode\Helpers\Statuses;
use Cashbox\Tinkoff\QrCode\Requests\Cancel;
use Cashbox\Tinkoff\QrCode\Requests\GetQR;
use Cashbox\Tinkoff\QrCode\Requests\GetState;
use Cashbox\Tinkoff\QrCode\Requests\Init;
use Cashbox\Tinkoff\QrCode\Resources\Details;
use Cashbox\Tinkoff\QrCode\Responses\QrCode;
use Cashbox\Tinkoff\QrCode\Responses\Refund;
use Cashbox\Tinkoff\QrCode\Responses\State;
use DragonCode\Contracts\Cashier\Http\Response;

class Driver extends BaseDriver
{
    protected $exception = Manager::class;

    protected $statuses = Statuses::class;

    protected $details = Details::class;

    public function start(): Response
    {
        $this->init();

        $request = GetQR::make($this->model);

        return $this->request($request, QrCode::class);
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

    protected function init(): void
    {
        $request = Init::make($this->model);

        $response = $this->request($request, Responses\Init::class);

        $external_id = $response->getExternalId();

        $details = $this->details($response->toArray());

        Model::updateOrCreate($this->payment, compact('external_id', 'details'));

        $this->payment->refresh();
    }
}
