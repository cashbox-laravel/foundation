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

use Cashbox\BankName\Auth\Basic;
use Cashbox\BankName\Auth\Constants\Keys;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\Http\Requests\TemplateAuthBasicRequest;

it('basic authorization', function () {
    fakeEvents();
    fakeTemplateHttp();

    $payment = createPayment(TypeEnum::templateDriver, 1234);

    $auth = templateAuth($payment, Basic::class, TemplateAuthBasicRequest::class);

    expect($auth->headers())->toBeArray()->toBeEmpty();
    expect($auth->options())->toBeArray()->toBeEmpty();

    expect($auth->body())->toBeArray()->toBe([
        Keys::TOKEN => 123,
    ]);
});
