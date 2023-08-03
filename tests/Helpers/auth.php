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

use Cashbox\Sber\Auth\Sign;
use Tests\Fixtures\App\Models\PaymentModel;
use Tests\Fixtures\Payments\SberAuth;
use Tests\Fixtures\Requests\SberAuthRequest;

function sberSign(PaymentModel $payment): Sign
{
    $config = $payment->cashboxDriver()->config;

    return new Sign(
        request: new SberAuthRequest(new SberAuth($payment, $config)),
        config : $config,
        extra  : [
            'url'   => 'https://via.placeholder.com/640x480.png/00eecc?text=consequatur',
            'scope' => 'https://api.sberbank.ru/order.create',
        ]
    );
}
