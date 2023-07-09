<?php

/*
 * This file is part of the "andrey-helldar/cashier-sber-auth" project.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Andrey Helldar <helldar@ai-rus.com>
 *
 * @copyright 2021 Andrey Helldar
 *
 * @license MIT
 *
 * @see https://github.com/andrey-helldar/cashier-sber-auth
 */

namespace Tests\Support\Cache;

use Helldar\CashierDriver\Sber\Auth\Support\Hash;
use Helldar\Support\Facades\Http\Builder;
use Tests\TestCase;

class GetTest extends TestCase
{
    protected $uri_create = 'https://dev.api.sberbank.ru/ru/prod/order/v1/creation';

    protected $pattern = '/^[\w]{8}-[\w]{4}-[\w]{4}-[\w]{4}-[\w]{12}$/';

    public function testBasic()
    {
        $first  = $this->getCache();
        $second = $this->getCache();
        $third  = $this->getCache();

        $this->assertMatchesRegularExpression($this->pattern, $first);
        $this->assertMatchesRegularExpression($this->pattern, $second);
        $this->assertMatchesRegularExpression($this->pattern, $third);

        $this->assertSame($first, $second);
        $this->assertSame($first, $third);
        $this->assertSame($second, $third);
    }

    protected function getCache(): string
    {
        $model = $this->model();

        $uri = Builder::parse($this->uri_create);

        $token = Hash::make()->get($model, $uri, self::SCOPE_CREATE);

        return $token->getAccessToken();
    }
}
