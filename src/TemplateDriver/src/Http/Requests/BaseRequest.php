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

namespace Cashbox\BankName\Technology\Http\Requests;

use Cashbox\BankName\Auth\Auth;
use Cashbox\BankName\Technology\Resources\TemplateDriverResource;
use Cashbox\Core\Http\Request;
use Cashbox\Core\Services\Auth as BaseSign;

/**
 * @property-read TemplateDriverResource $resource
 */
abstract class BaseRequest extends Request
{
    protected string $productionHost = 'https://example.com';

    protected BaseSign|string|null $auth = Auth::class;

    protected function clientId(): string
    {
        return $this->resource->config->credentials->clientId;
    }

    protected function clientSecret(): string
    {
        return $this->resource->config->credentials->clientSecret;
    }
}
