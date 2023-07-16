<?php

declare(strict_types=1);

use Cashbox\Tinkoff\Auth\Services\Hash;

it('checks the basic data', function () {
    $clientId     = 'foo';
    $clientSecret = 'bar';

    $hashed = Hash::get($clientId, $clientSecret, [
        'field1' => 'qwe',
        'field2' => 'rty',
    ], false);

    expect($hashed->clientId)->toBe($clientId);
    expect($hashed->clientSecret)->toBe($clientSecret);
});

it('checks the hashed data', function (array $data) {
    $clientId     = 'foo';
    $clientSecret = 'bar';
    $hash         = '53ed3ed16f10e22841fab015a863170d14fa551e9d2b2c411d1c0570caa69c99';

    $hashed = Hash::get($clientId, $clientSecret, $data, true);

    expect($hashed->clientId)->toBe($clientId);
    expect($hashed->clientSecret)->toBe($hash);
})->with([
    [['field1' => 'qwe', 'field2' => 'rty']],
    [['field2' => 'rty', 'field1' => 'qwe']],
]);
