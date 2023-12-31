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

declare(strict_types=1);

namespace CashierProvider\Sber\QrCode\Requests;

use CashierProvider\Core\Facades\Config\Main;
use CashierProvider\Core\Facades\Helpers\Date;
use CashierProvider\Core\Http\Request;
use CashierProvider\Sber\Auth\Auth;
use Illuminate\Support\Carbon;

abstract class BaseRequest extends Request
{
    protected $production_host = 'https://api.sberbank.ru';

    protected $dev_host = 'https://dev.api.sberbank.ru';

    protected $auth = Auth::class;

    public function getRawHeaders(): array
    {
        return [
            'Accept'       => 'application/json',
            'Content-Type' => 'application/json',
        ];
    }

    public function getHttpOptions(): array
    {
        if (Main::isProduction()) {
            $cert = $this->getCertificateData();

            return compact('cert');
        }

        return [];
    }

    protected function uniqueId(): string
    {
        return $this->model->getUniqueId();
    }

    protected function currentTime(): string
    {
        $date = Carbon::now();

        return Date::toString($date);
    }

    protected function getCertificateData(): array
    {
        return [
            $this->model->getCertificatePath(),
            $this->model->getCertificatePassword(),
        ];
    }
}
