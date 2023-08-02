<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use PHPUnit\Framework\Assert;
use Tests\Fixtures\App\Enums\StatusEnum;

expect()->extend('toBeHasCashbox', function () {
    expect($this->value->cashbox()->exists())->toBeTrue();

    return $this;
});

expect()->extend('toBeDoesntHaveCashbox', function () {
    expect($this->value->cashbox()->doesntExist())->toBeTrue();

    return $this;
});

expect()->extend('toBeUrl', function () {
    $message = sprintf('The value must be a valid URL: "%s"', $this->value);

    Assert::assertTrue(Str::isUrl($this->value), $message);

    return $this;
});

expect()->extend('toBeStatus', function (StatusEnum $status) {
    $expected = sprintf('"%s" (%s)', $status->value, $status->name);
    $actual   = sprintf('"%s" (%s)', $this->value->status->value, $this->value->status->name);

    $message = sprintf('The status must be %s, %s given.', $expected, $actual);

    expect($this->value->refresh()->status)->toBe($status, $message);

    return $this;
});
