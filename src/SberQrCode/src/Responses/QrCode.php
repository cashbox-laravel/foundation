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
 * @see https://github.com/cashbox-laravel/foundation
 */

declare(strict_types=1);

namespace CashierProvider\Sber\QrCode\Responses;

use CashierProvider\Core\Http\ResponseInfo;

class QrCode extends ResponseInfo
{
    public const KEY_URL = 'url';

    protected $map = [
        self::KEY_EXTERNAL_ID => 'status.order_id',

        self::KEY_STATUS => 'status.order_state',

        self::KEY_URL => 'status.order_form_url',
    ];

    public function getUrl(): ?string
    {
        return $this->value(self::KEY_URL);
    }

    public function isEmpty(): bool
    {
        return empty($this->getExternalId()) || empty($this->getUrl());
    }
}
