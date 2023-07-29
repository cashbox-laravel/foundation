<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;

expect()->extend('toBeHasCashbox', function (string $message = '') {
    expect($this->value->cashbox()->exists())->toBeTrue($message);

    return $this;
});

expect()->extend('toBeDoesntHaveCashbox', function (string $message = '') {
    expect($this->value->cashbox()->doesntExist())->toBeTrue($message);

    return $this;
});

expect()->extend('toBeUrl', function (string $message = '') {
    Assert::assertTrue(Str::isUrl($this->value), $message);

    return $this;
});
