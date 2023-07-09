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

namespace SberAuth\src\Support;

use Helldar\Cashier\Facades\Helpers\Http;
use SberAuth\src\Constants\Keys;
use SberAuth\src\Exceptions\Manager as ExceptionManager;
use SberAuth\src\Facades\Cache as CacheRepository;
use SberAuth\src\Http\Request;
use SberAuth\src\Objects\Query;
use SberAuth\src\Resources\AccessToken;
use Helldar\Contracts\Cashier\Http\Request as RequestContract;
use Helldar\Contracts\Cashier\Resources\Model;
use Helldar\Contracts\Exceptions\Manager;
use Helldar\Contracts\Http\Builder;
use Helldar\Support\Concerns\Makeable;

class Hash
{
    use Makeable;

    public function get(Model $model, Builder $uri, string $scope): AccessToken
    {
        $query = $this->query($model, $scope);

        return CacheRepository::get($query, function (Query $query) use ($uri) {
            $request = $this->request($query->getModel(), $uri, $query->getScope());

            $response = $this->post($request);

            return $this->makeToken(array_merge($response, [
                Keys::CLIENT_ID => $query->getModel()->getClientId(),
            ]));
        });
    }

    public function forget(Model $model, string $scope): void
    {
        $query = $this->query($model, $scope);

        CacheRepository::forget($query);
    }

    protected function post(RequestContract $request): array
    {
        return Http::post($request, $this->exceptions());
    }

    protected function query(Model $model, string $scope): Query
    {
        return Query::make(compact('model', 'scope'));
    }

    protected function request(Model $model, Builder $uri, string $scope): RequestContract
    {
        return Request::make($model)
            ->setHost($uri->getBaseUrl())
            ->setScope($scope);
    }

    protected function makeToken(array $response): AccessToken
    {
        return AccessToken::make($response);
    }

    protected function exceptions(): Manager
    {
        return new ExceptionManager();
    }
}
