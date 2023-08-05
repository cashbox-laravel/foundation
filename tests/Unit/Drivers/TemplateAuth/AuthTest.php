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

use Cashbox\BankName\Auth\Auth;
use Cashbox\BankName\Auth\Constants\Keys;
use Cashbox\Core\Enums\CurrencyEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\Http\Requests\TemplateAuthRequest;
use Tests\Fixtures\Resources\TemplateAuthResource;

it('clean data', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::templateAuth, 1234);

    $request = new TemplateAuthRequest(
        new TemplateAuthResource($payment, $payment->cashboxDriver()->config)
    );

    expect($request->headers())->toBeEmpty();
    expect($request->options())->toBeEmpty();

    expect($request->body())->toBe([
        'paymentId' => (string) $payment->id,
        'sum'       => $payment->price,
        'currency'  => CurrencyEnum::USD->value,
    ]);
});

it('signed data', function () {
    fakeEvents();

    $payment = createPayment(TypeEnum::templateAuth, 1234);

    $request = new TemplateAuthRequest(
        new TemplateAuthResource($payment, $payment->cashboxDriver()->config)
    );

    expect($request->sign()->headers())->toBeEmpty();
    expect($request->sign()->options())->toBeEmpty();

    expect($request->sign())->toBeInstanceOf(Auth::class);

    expect($request->sign()->body())->toBe([
        'paymentId'    => (string) $payment->id,
        'sum'          => $payment->price,
        'currency'     => CurrencyEnum::USD->value,
        Keys::TERMINAL => 'qwerty',
        Keys::TOKEN    => '57f7acb468d49f49dcaf10c0b193ef8303c84ce5fb35c40b53fea58ed877cb4f',
    ]);
});
