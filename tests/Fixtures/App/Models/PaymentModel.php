<?php

declare(strict_types=1);

namespace Tests\Fixtures\App\Models;

use Cashbox\Core\Billable;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;

class PaymentModel extends Model
{
    use Billable;

    protected $table = 'payments';

    protected $fillable = [
        'price',
        'type',
        'status',
    ];

    protected $casts = [
        'type'   => TypeEnum::class,
        'status' => StatusEnum::class,

        'price' => 'int',
    ];
}
