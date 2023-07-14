<?php

declare(strict_types=1);

use Cashbox\Cash\Driver;
use Cashbox\Core\Data\Config\ConfigData;
use Cashbox\Core\Exceptions\Internal\ConfigCannotBeEmptyException;
use Cashbox\Core\Facades\Config;
use Tests\Fixtures\Details\CashPaymentDetails;
use Tests\Fixtures\Enums\StatusEnum;
use Tests\Fixtures\Enums\TypeEnum;
use Tests\Fixtures\Models\PaymentModel;

it('should return an error when running an empty config file', function () {
    forget(ConfigData::class, Config::class);

    config(['cashbox' => []]);

    Config::isProduction();
})
    ->throws(ConfigCannotBeEmptyException::class)
    ->expectExceptionMessage('Error reading configuration');

it('is production', function () {
    expect(
        Config::isProduction()
    )->toBeFalse();
});

it('checks the payment block', function () {
    $data = Config::payment();

    expect($data->model)->toBe(PaymentModel::class);

    expect($data->attribute->type)->toBe('type');
    expect($data->attribute->status)->toBe('status');
    expect($data->attribute->createdAt)->toBe('created_at');

    expect($data->status->new)->toBe(StatusEnum::new);
    expect($data->status->success)->toBe(StatusEnum::success);
    expect($data->status->waitRefund)->toBe(StatusEnum::waitRefund);
    expect($data->status->refund)->toBe(StatusEnum::refund);
    expect($data->status->failed)->toBe(StatusEnum::failed);

    expect($data->drivers)->toBeArray();
    expect($data->drivers)->toBe([
        TypeEnum::cash() => TypeEnum::cash(),
    ]);
});

it('checks the details data block', function () {
    $data = Config::details();

    expect($data->connection)->toBe('testing');

    expect($data->table)->toBe('cashbox_details');
});

it('checks the logs block', function () {
    $data = Config::logs();

    expect($data->info)->toBeNull();
    expect($data->error)->toBeNull();
});

it('checks the queue block', function () {
    $data = Config::queue();

    expect($data->connection)->toBe('sync');
    expect($data->tries)->toBe(50);
    expect($data->exceptions)->toBe(3);

    expect($data->name->start)->toBeNull();
    expect($data->name->verify)->toBeNull();
    expect($data->name->refund)->toBeNull();
});

it('should be correct tries', function (int $set, int $tries, int $exceptions) {
    forget(ConfigData::class, Config::class);

    config(['cashbox.queue.tries' => $set]);
    config(['cashbox.queue.exceptions' => $set]);

    expect(Config::queue()->tries)->toBe($tries);
    expect(Config::queue()->exceptions)->toBe($exceptions);
})->with([
    [-10, 1, 1],
    [794, 50, 10],
    [51, 50, 10],
    [50, 50, 10],
    [1, 1, 1],
]);

it('checks the verify block', function (int $set, int $delay, int $timeout) {
    forget(ConfigData::class, Config::class);

    config(['cashbox.verify.delay' => $set]);
    config(['cashbox.verify.timeout' => $set]);

    expect(Config::verify()->delay)->toBe($delay);
    expect(Config::verify()->timeout)->toBe($timeout);
})->with([
    [-10, 0, 0],
    [0, 0, 0],
    [1, 1, 1],
    [10, 10, 10],
    [50, 50, 30],
    [100, 60, 30],
]);

it('checks the refund block', function (int $set, int $delay) {
    forget(ConfigData::class, Config::class);

    config(['cashbox.auto_refund.delay' => $set]);

    expect(Config::refund()->delay)->toBe($delay);
})->with([
    [-10, 0],
    [0, 0],
    [1, 1],
    [10, 10],
    [50, 50],
    [100, 100],
    [600, 600],
    [700, 600],
]);

it('should return an error when accessing a non-existent driver', function () {
    expect(
        Config::driver('qwerty')
    )->toBeNull();
});

it('should check the correctness of the driver settings', function () {
    // cash
    $cash = Config::driver(TypeEnum::cash());

    expect($cash->driver)->toBe(Driver::class);
    expect($cash->details)->toBe(CashPaymentDetails::class);
    expect($cash->credentials)->toBeNull();
    expect($cash->queue)->toBeNull();

    expect($cash->getQueue()->start)->toBeNull();
    expect($cash->getQueue()->verify)->toBeNull();
    expect($cash->getQueue()->refund)->toBeNull();
});

it(
    'should check the correctness of getting the name of the queue for the job',
    function (
        string $name,
        ?string $ms,
        ?string $mv,
        ?string $mr,
        ?string $ds,
        ?string $dv,
        ?string $dr,
        ?string $bs,
        ?string $bv,
        ?string $br
    ) {
        forget(ConfigData::class, Config::class);

        config(['cashbox.queue.name.start' => $ms]);
        config(['cashbox.queue.name.verify' => $mv]);
        config(['cashbox.queue.name.refund' => $mr]);

        config(["cashbox.drivers.$name.start" => $ds]);
        config(["cashbox.drivers.$name.verify" => $dv]);
        config(["cashbox.drivers.$name.refund" => $dr]);

        $item = Config::driver($name);

        expect($item->getQueue()->start)->toBe($bs);
        expect($item->getQueue()->verify)->toBe($bv);
        expect($item->getQueue()->refund)->toBe($br);
    }
)->with([
    [TypeEnum::cash(), 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q1', 'q2', 'q3'],
    [TypeEnum::cash() . '_1', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q4', 'q5', 'q6'],
    [TypeEnum::cash() . '_2', 'q1', 'q2', 'q3', 'q4', 'q5', 'q6', 'q4', null, null],
]);
