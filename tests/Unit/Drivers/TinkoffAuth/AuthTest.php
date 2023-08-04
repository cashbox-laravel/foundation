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

use Cashbox\Tinkoff\Auth\Services\Hash;

it('basic data', function () {
    $clientId     = 'foo';
    $clientSecret = 'bar';

    $hashed = Hash::get($clientId, $clientSecret, [
        'field1' => 'qwe',
        'field2' => 'rty',
        'field3' => 'asd',
    ], false);

    expect($hashed->clientId)->toBe($clientId);
    expect($hashed->clientSecret)->toBe($clientSecret);
});

it('hashed data', function (array $data) {
    $clientId     = 'foo';
    $clientSecret = 'bar';
    $hash         = '7959ae1ec20a02490d8d2f01d3704a2543e70c43cbe0c672209741263ef8048f';

    $hashed = Hash::get($clientId, $clientSecret, $data, true);

    expect($hashed->clientId)->toBe($clientId);
    expect($hashed->clientSecret)->toBe($hash);
})->with([
    [['field1' => 'qwe', 'field2' => 'rty', 'field3' => 'asd']],
    [['field1' => 'qwe', 'field3' => 'asd', 'field2' => 'rty']],
    [['field2' => 'rty', 'field1' => 'qwe', 'field3' => 'asd']],
    [['field2' => 'rty', 'field3' => 'asd', 'field1' => 'qwe']],
    [['field3' => 'asd', 'field1' => 'qwe', 'field2' => 'rty']],
    [['field3' => 'asd', 'field2' => 'rty', 'field1' => 'qwe']],
]);
