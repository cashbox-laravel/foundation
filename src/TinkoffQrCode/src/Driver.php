<?php

/*
 * This file is part of the "cashier-provider/tinkoff-qr" project.
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
 * @see https://github.com/cashier-provider/tinkoff-qr
 */

namespace TinkoffQr\src;

use CashierProvider\Core\Facades\Helpers\Model;
use CashierProvider\Core\Services\Driver as BaseDriver;
use TinkoffQr\src\Exceptions\Manager;
use TinkoffQr\src\Helpers\Statuses;
use TinkoffQr\src\Requests\Cancel;
use TinkoffQr\src\Requests\GetQR;
use TinkoffQr\src\Requests\GetState;
use TinkoffQr\src\Requests\Init;
use TinkoffQr\src\Resources\Details;
use TinkoffQr\src\Responses\QrCode;
use TinkoffQr\src\Responses\Refund;
use TinkoffQr\src\Responses\State;
use Helldar\Contracts\Cashier\Http\Response;

class Driver extends BaseDriver
{
    protected $exceptions = Manager::class;

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

        $response = $this->request($request, \TinkoffQr\src\Responses\Init::class);

        $external_id = $response->getExternalId();

        $details = $this->details($response->toArray());

        Model::updateOrCreate($this->payment, compact('external_id', 'details'));

        $this->payment->refresh();
    }
}
