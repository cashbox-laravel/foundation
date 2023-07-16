<?php

declare(strict_types=1);

use Cashbox\Cash\Driver as CashDriver;
use Cashbox\Core\Exceptions\Internal\ConfigCannotBeEmptyException;
use Cashbox\Core\Exceptions\Internal\IncorrectDriverException;
use Cashbox\Core\Exceptions\Internal\IncorrectResourceException;
use Cashbox\Core\Facades\Config;
use Cashbox\Core\Resources\Resource;
use Cashbox\Core\Services\Driver;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;
use Tests\Fixtures\Data\FakeData;
use Tests\Fixtures\Drivers\Cash\Payments\Cash;

it('should return an error when running an empty config file', function () {
    forgetConfig();

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
        TypeEnum::cash() => TypeEnum::cash,
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

it('checks the refund block', function () {
    forgetConfig();

    expect(Config::refund()->delay)->toBe(600);
});

it('should return an error when accessing a non-existent driver', function () {
    expect(
        Config::driver('qwerty')
    )->toBeNull();
});

it('should check the driver settings', function () {
    // cash
    $cash = Config::driver(TypeEnum::cash());

    expect($cash->driver)->toBe(CashDriver::class);
    expect($cash->resource)->toBe(Cash::class);
    expect($cash->credentials)->toBeNull();
    expect($cash->queue)->toBeNull();

    expect($cash->getQueue()->start)->toBeNull();
    expect($cash->getQueue()->verify)->toBeNull();
    expect($cash->getQueue()->refund)->toBeNull();
});

it('should check the driver instance', function () {
    forgetConfig();

    $name = TypeEnum::cash();

    config(["cashbox.drivers.$name.driver" => FakeData::class]);

    Config::driver($name)->driver;
})
    ->throws(IncorrectDriverException::class)
    ->expectExceptionMessage(
        sprintf('The "%s" class must implement "%s".', FakeData::class, Driver::class)
    );

it('should check the resource instance', function () {
    forgetConfig();

    $name = TypeEnum::cash();

    config(["cashbox.drivers.$name.resource" => FakeData::class]);

    Config::driver($name)->resource;
})
    ->throws(IncorrectResourceException::class)
    ->expectExceptionMessage(
        sprintf('The "%s" class must implement "%s".', FakeData::class, Resource::class)
    );

it(
    'should check the correctness of getting the name of the queue for the job',
    function (array $main, array $driver, array $expected) {
        forgetConfig();

        $name = TypeEnum::cash();

        config(['cashbox.queue.name' => $main]);
        config(["cashbox.drivers.$name.queue" => $driver]);

        $item = Config::driver($name);

        expect($item->getQueue()->start)->toBe($expected['start']);
        expect($item->getQueue()->verify)->toBe($expected['verify']);
        expect($item->getQueue()->refund)->toBe($expected['refund']);
    }
)->with([
    'filled'       => [
        driverData('q1', 'q2', 'q3'),
        driverData('q4', 'q5', 'q6'),
        driverData('q4', 'q5', 'q6'),
    ],
    'partial'      => [
        driverData('q1', 'q2', 'q3'),
        driverData('q4', null, null),
        driverData('q4', null, null),
    ],
    'driver empty' => [
        driverData('q1', 'q2', 'q3'),
        [],
        driverData(null, null, null),
    ],
    'main empty'   => [
        [],
        driverData('q4', 'q5', 'q6'),
        driverData('q4', 'q5', 'q6'),
    ],
    'full empty'   => [
        [],
        [],
        driverData(null, null, null),
    ],
]);

function driverData(?string $start, ?string $verify, ?string $refund): array
{
    return compact('start', 'verify', 'refund');
}
