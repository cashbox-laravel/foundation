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

namespace CashierProvider\Sber\QrCode\Resources;

use CashierProvider\Core\Resources\Model as BaseModel;

abstract class Model extends BaseModel
{
    abstract public function getCertificatePath(): ?string;

    abstract public function getCertificatePassword(): ?string;

    abstract public function getMemberId(): string;

    abstract public function getTerminalId(): string;
}
