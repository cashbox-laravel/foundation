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

namespace Cashbox\Tinkoff\Credit\Http\Responses;

use Cashbox\Core\Http\Response as BaseResponse;
use Spatie\LaravelData\Attributes\MapInputName;

class Response extends BaseResponse
{
    public ?string $status;

    #[MapInputName('link')]
    public ?string $url;

    protected function getExtra(): array
    {
        return [
            'status' => $this->status,
            'url'    => $this->url,
        ];
    }

    protected function getStatus(): ?string
    {
        return $this->status;
    }
}
