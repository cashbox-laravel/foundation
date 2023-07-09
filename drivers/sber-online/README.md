# Sber Online Cashier Driver

![cashier provider sber online](https://preview.dragon-code.pro/cashier-provider/sber-online.svg?brand=laravel)

[![Stable Version][badge_stable]][link_packagist]
[![Unstable Version][badge_unstable]][link_packagist]
[![Total Downloads][badge_downloads]][link_packagist]
[![License][badge_license]][link_license]


## Installation

To get the latest version of `Sber Online Cashier Driver`, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require cashier-provider/sber-online
```

Or manually update `require` block of `composer.json` and run `composer update`.

```json
{
    "require": {
        "cashier-provider/sber-online": "^1.0"
    }
}
```

## Using

> **Note**:
>
> This project is the driver for [`Cashier Provider`](https://github.com/cashier-provider/core).
>
> Member ID and Terminal ID must be provided by the bank manager in response to the agreement concluded with the bank.
>
> You can get the values of the `username` and `password` from your personal bank manager.


### Configuration

Add your driver information to the `config/cashier.php` file:

```php
use App\Models\Payment;
use App\Payments\Sber as SberOnlineDetails;
use CashierProvider\Core\Constants\Driver;
use CashierProvider\Sber\Online\Driver as SberOnlineDriver;

return [
    'payment' => [
        'map' => [
            Payment::TYPE_SBER => 'sber_online'
        ]
    ],

    'drivers' => [
        'sber_online' => [
            Driver::DRIVER  => SberOnlineDriver::class,
            Driver::DETAILS => SberOnlineDetails::class,

            Driver::CLIENT_ID       => env('CASHIER_SBER_ONLINE_USERNAME'),
            Driver::CLIENT_SECRET   => env('CASHIER_SBER_ONLINE_PASSWORD'),

            'success_url' => env('CASHIER_SBER_ONLINE_SUCCESS_URL'),
            'fail_url' => env('CASHIER_SBER_ONLINE_FAIL_URL'),

            'order_prefix' => env('CASHIER_SBER_ONLINE_ORDER_PREFIX')
        ]
    ]
];
```

### Resource

Create a model resource class inheriting from `CashierProvider\Sber\Online\Resources\Model` in your application.

Use the `$this->model` link to refer to the payment model. When executed, the `$model` parameter will contain the payment instance.

```php
namespace App\Payments;

use CashierProvider\Sber\Online\Resources\Model;

class Sber extends Model
{
    protected function paymentId(): string
    {
        return (string) $this->model->id;
    }

    protected function sum(): float
    {
        return (float) $this->model->sum;
    }

    protected function currency(): int
    {
        return $this->model->currency;
    }

    protected function createdAt(): Carbon
    {
        return $this->model->created_at;
    }

    public function getSuccessUrl(): string
    {
        return config('cashier.drivers.sber_online.success_url');
    }

    public function getFailUrl(): string
    {
        return config('cashier.drivers.sber_online.fail_url');
    }

    public function getOrderPrefix(): string
    {
        return config('cashier.drivers.sber_online.order_prefix');
    }
}
```

#### Custom Authentication

In some cases, the application can send requests to the bank from different terminals. For example, when one application serves payments of several companies.

In order for the payment to be authorized with the required authorization data, you can override the `clientId` and `clientSecret` methods:

```php
namespace App\Payments;

use CashierProvider\Sber\Online\Resources\Model;
use Illuminate\Support\Facades\Storage;

class Sber extends Model
{
    protected $bank;

    protected function paymentId(): string
    {
        return (string) $this->model->id;
    }

    protected function sum(): float
    {
        return (float) $this->model->sum;
    }

    protected function currency(): int
    {
        return $this->model->currency;
    }

    protected function createdAt(): Carbon
    {
        return $this->model->created_at;
    }

    protected function clientId(): string
    {
        return $this->bank()->client_id;
    }

    protected function clientSecret(): string
    {
        return $this->bank()->client_secret;
    }

    public function getSuccessUrl(): string
    {
        return $this->bank()->success_url;
    }

    public function getFailUrl(): string
    {
        return $this->bank()->fail_url;
    }

    public function getOrderPrefix(): string
    {
        return $this->bank()->order_prefix;
    }

    protected function bank()
    {
        if (! empty($this->bank)) {
            return $this->bank;
        }

        return $this->bank = $this->model->types()
            ->where('type', Payment::TYPE_SBER)
            ->firstOrFail()
            ->bank;
    }
}
```

### Response

All requests to the bank and processing of responses are carried out by the [`Cashier Provider`](https://github.com/cashier-provider/core) project.

To get a link, contact him through the cast:

```php
use App\Models\Payment;

public function getOnline(Payment $payment): string
{
    return $payment->cashier->details->getUrl();
}
```

### Available Methods And Details Data

```php
$payment->cashier->external_id
// Returns the bank's transaction ID for this operation

$payment->cashier->details->getStatus(): ?string
// Returns the text status from the bank
// For example, `CREATED`.

$payment->cashier->details->getUrl(): ?string
// If the request to get the link was successful, it will return the URL
// For example, `https://sberbank.ru/qr/?dynamicQr=<hash>`

$payment->cashier->details->toArray(): array
// Returns an array of status and URL.
// For example,
//
// [
//     'url' => 'https://sberbank.ru/qr/?dynamicQr=<hash>',
//     'status' => 'CREATED'
// ]
```

[badge_downloads]:      https://img.shields.io/packagist/dt/cashier-provider/sber-qr.svg?style=flat-square

[badge_license]:        https://img.shields.io/packagist/l/cashier-provider/sber-qr.svg?style=flat-square

[badge_stable]:         https://img.shields.io/github/v/release/cashier-provider/sber-qr?label=stable&style=flat-square

[badge_unstable]:       https://img.shields.io/badge/unstable-dev--main-orange?style=flat-square

[link_license]:         LICENSE

[link_packagist]:       https://packagist.org/packages/cashier-provider/sber-qr
