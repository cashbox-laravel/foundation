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

namespace Tests\Fixtures\App\Models;

use Cashbox\Core\Billable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\Factories\PaymentModelFactory;

class PaymentModel extends Model
{
    use Billable;
    use HasFactory;

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

    protected static function newFactory(): PaymentModelFactory
    {
        return PaymentModelFactory::new();
    }
}
