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

namespace CashierProvider\BankName\Technology\Requests;

use CashierProvider\BankName\Auth\Auth;
use CashierProvider\Core\Facades\Config\Main;
use CashierProvider\Core\Http\Request;

abstract class BaseRequest extends Request
{
    protected $production_host = 'https://api.example.com';

    protected $dev_host = 'https://dev.api.example.com';

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

    protected function getCertificateData(): array
    {
        return [
            $this->model->getCertificatePath(),
            $this->model->getCertificatePassword(),
        ];
    }
}
