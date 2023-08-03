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

use Cashbox\Core\Data\Config\Drivers\CredentialsData;
use Cashbox\Sber\Auth\Services\Hash;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

it('validates the authorization request', function () {
    fakeSberAuth();

    $credentials = CredentialsData::from([
        'clientId'     => 'qwerty',
        'clientSecret' => 'qwerty123',
    ]);

    $service = Hash::get($credentials, '1234567890', 'https://example.com', 'create');

    expect($service->clientId)->toBe('qwerty');
    expect($service->clientSecret)->toBe('foobar123');
    expect($service->expiresIn->getTimestamp())->toBe(now()->addSeconds(60)->timestamp);

    Http::assertSent(function (Request $request) {
        $data = [
            'grant_type' => 'client_credentials',
            'scope'      => 'create',
        ];

        return $request->hasHeader('X-IBM-Client-Id', 'qwerty')
            && $request->hasHeader('Authorization', base64_encode('qwerty:qwerty123'))
            && $request->hasHeader('RqUID')
            && $request->hasHeader('Accept', 'application/json')
            && $request->hasHeader('Content-Type', 'application/x-www-form-urlencoded')
            && $request->data() === $data
            && Str::is('*/ru/prod/tokens/v2/oauth', $request->url());
    });
});
