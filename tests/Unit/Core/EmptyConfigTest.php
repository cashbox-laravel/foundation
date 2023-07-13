<?php

declare(strict_types=1);

use Cashbox\Core\Exceptions\Internal\ConfigCannotBeEmptyException;

it('must be a empty config', function () {
    config(['cashbox' => []]);
})->throws(
    ConfigCannotBeEmptyException::class,
    'Error reading configuration. Check the existence of the "config/cashbox.php" file.'
);