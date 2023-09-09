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

use Cashbox\BankName\Auth\Tokens\BasicToken;
use Cashbox\BankName\Auth\Tokens\HashToken;

it('base signature', function (array $data) {
    $clientId     = 'foo';
    $clientSecret = 'bar';

    $token = BasicToken::get($clientId, $clientSecret, $data);

    expect($token->clientId)->toBe($clientId);
    expect($token->clientSecret)->toBe($clientSecret);
    expect($token->expiresIn)->toBeInstanceOf(DateTimeInterface::class);
})->with([
    [['field1' => 'qwe', 'field2' => 'rty', 'field3' => 'asd']],
    [['field1' => 'qwe', 'field3' => 'asd', 'field2' => 'rty']],
    [['field2' => 'rty', 'field1' => 'qwe', 'field3' => 'asd']],
    [['field2' => 'rty', 'field3' => 'asd', 'field1' => 'qwe']],
    [['field3' => 'asd', 'field1' => 'qwe', 'field2' => 'rty']],
    [['field3' => 'asd', 'field2' => 'rty', 'field1' => 'qwe']],
]);

it('hashed signature', function (array $data) {
    $clientId     = 'foo';
    $clientSecret = 'bar';
    $hash         = '7959ae1ec20a02490d8d2f01d3704a2543e70c43cbe0c672209741263ef8048f';

    $token = HashToken::get($clientId, $clientSecret, $data);

    expect($token->clientId)->toBe($clientId);
    expect($token->clientSecret)->toBe($hash);
    expect($token->expiresIn)->toBeInstanceOf(DateTimeInterface::class);
})->with([
    [['field1' => 'qwe', 'field2' => 'rty', 'field3' => 'asd']],
    [['field1' => 'qwe', 'field3' => 'asd', 'field2' => 'rty']],
    [['field2' => 'rty', 'field1' => 'qwe', 'field3' => 'asd']],
    [['field2' => 'rty', 'field3' => 'asd', 'field1' => 'qwe']],
    [['field3' => 'asd', 'field1' => 'qwe', 'field2' => 'rty']],
    [['field3' => 'asd', 'field2' => 'rty', 'field1' => 'qwe']],
]);
