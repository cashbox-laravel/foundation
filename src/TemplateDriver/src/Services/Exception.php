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

namespace Cashbox\BankName\Technology\Services;

use Cashbox\Core\Exceptions\External\UnauthorizedHttpException;
use Cashbox\Core\Services\Exception as BaseException;

class Exception extends BaseException
{
    protected array $codes = [
        403 => UnauthorizedHttpException::class,
    ];

    protected array $failedKey = ['Success'];

    protected array $codeKeys = ['ErrorCode'];

    protected array $reasonKeys = ['Details', 'Message'];
}
