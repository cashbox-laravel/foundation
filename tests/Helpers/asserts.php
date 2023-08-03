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
    /** @var \Tests\Fixtures\App\Models\PaymentModel $item */
    $item = $this->value->refresh();

    $message = sprintf(
        'The status must be "%s::%s", "%s::%s" given for payment ID %s.',
        get_class($status),
        $status->name,
        get_class($item->status),
        $item->status->name,
        $this->value->getKey()
    );

    expect($item->status)->toBe($status, $message);

    return $this;
});
