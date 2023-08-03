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

declare(strict_types=1);

namespace Cashbox\Tinkoff\QrCode\Requests;

class Init extends BaseRequest
{
    protected $path = '/v2/Init';

    protected $secure = false;

    public function getRawBody(): array
    {
        return [
            'OrderId' => $this->model->getPaymentId(),

            'Amount' => $this->model->getSum(),

            'Currency' => $this->model->getCurrency(),
        ];
    }
}
