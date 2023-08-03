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

use Tests\Fixtures\App\Enums\TypeEnum;

it('should check authorization', function () {
    fakeSberAuth();

    $payment = createPayment(TypeEnum::sberQrCode);

    $sign = sberSign($payment);

    expect($sign->headers())->toBe([
        'X-IBM-Client-Id'    => 'qwerty',
        'x-Introspect-RqUID' => 'uniqueId',
        'Authorization'      => 'Bearer qwerty123',
    ]);

    expect($sign->options())->toBe([]);

    expect($sign->body())->toBe([]);
});
