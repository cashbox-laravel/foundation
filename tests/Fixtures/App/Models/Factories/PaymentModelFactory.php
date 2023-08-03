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

namespace Tests\Fixtures\App\Models\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Tests\Fixtures\App\Enums\StatusEnum;
use Tests\Fixtures\App\Enums\TypeEnum;
use Tests\Fixtures\App\Models\PaymentModel;

class PaymentModelFactory extends Factory
{
    protected $model = PaymentModel::class;

    public function definition(): array
    {
        return [
            'type'   => TypeEnum::outside,
            'status' => StatusEnum::new,
            'price'  => $this->faker->randomNumber(4),
        ];
    }
}
