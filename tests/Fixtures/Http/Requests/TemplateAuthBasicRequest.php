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

namespace Tests\Fixtures\Http\Requests;

use Cashbox\BankName\Auth\Basic;
use Cashbox\Core\Http\Request as BaseRequest;
use Cashbox\Core\Services\Auth;

class TemplateAuthBasicRequest extends BaseRequest
{
    protected string $productionHost = 'https://example.com';

    protected string $productionUri = '/foo';

    protected Auth|string|null $auth = Basic::class;

    public function body(): array
    {
        return [
            'paymentId' => $this->resource->paymentId(),
            'sum'       => $this->resource->sum(),
            'currency'  => $this->resource->currency(),
        ];
    }
}
