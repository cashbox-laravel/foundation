<?php

declare(strict_types=1);

namespace App\Commands;

use Cerbero\JsonParser\JsonParser;
use DragonCode\Support\Facades\Filesystem\File;
use DragonCode\Support\Facades\Helpers\Arr;
use DragonCode\Support\Helpers\Ables\Arrayable;
use Illuminate\Support\Arr as IA;

use function str_starts_with;

class Composer extends Command
{
    protected array $main = [];

    protected string $exclude = 'cashbox/';

    protected function handle(string $source, string $target): void
    {
        $composer = $this->load($target . '/composer.json');

        $this->copyToDriver($composer, 'license');
        $this->copyToDriver($composer, 'authors');
        $this->copyToDriver($composer, 'support');
        $this->copyToDriver($composer, 'funding');
        $this->copyToDriver($composer, 'minimum-stability', 'stable');
        $this->copyToDriver($composer, 'prefer-stable', true);
        $this->copyToDriver($composer, 'require.php');
        $this->copyToDriver($composer, 'config');
        $this->copyToDriver($composer, 'extra.branch-alias');
        $this->copyToDriver($composer, 'extra.thanks');

        $this->copyToDriverIntersect($composer, 'require');

        $this->copyToMain($composer, 'keywords');
        $this->copyToMain($composer, 'require');

        $this->store($target, $composer);
    }

    protected function prepare(): void
    {
        $this->main = $this->load($this->basePath('composer.json'));
    }

    protected function finish(): void
    {
        $this->store($this->basePath(), $this->main);
    }

    protected function store(string $target, array $composer): void
    {
        $flags = JSON_PRETTY_PRINT ^ JSON_UNESCAPED_SLASHES ^ JSON_UNESCAPED_UNICODE;

        file_put_contents($target . '/composer.json', json_encode($composer, $flags) . PHP_EOL);

        $this->composerLock($target . '/composer.lock');
    }

    protected function copyToDriver(&$array, string $key, mixed $default = null): void
    {
        IA::set($array, $key, $this->fromMain($key, $default));
    }

    protected function copyToDriverIntersect(&$array, string $key): void
    {
        foreach (Arr::get($this->main, $key) as $package => $version) {
            if (IA::has($array, $key . '.' . $package)) {
                IA::set($array, $key . '.' . $package, $version);
            }
        }
    }

    protected function copyToMain($array, string $key): void
    {
        $main = Arr::get($this->main, $key);
        $driver = Arr::get($array, $key);

        $items = Arr::of($driver)
            ->merge($main)
            ->filter(fn (int|string $key) => ! str_starts_with((string) $key, $this->exclude), ARRAY_FILTER_USE_KEY)
            ->when(
                fn (Arrayable $arr) => is_numeric($arr->keys()->first()),
                fn (Arrayable $arr) => $arr->unique()->sort()->values(),
                fn (Arrayable $arr) => $arr->ksort()
            )->toArray();

        IA::set($this->main, $key, $items);
    }

    protected function fromMain(string $key, mixed $default = null): mixed
    {
        return Arr::get($this->main, $key, $default);
    }

    protected function composerLock(string $path): void
    {
        File::ensureDelete($path);
    }

    protected function load(string $path): array
    {
        return JsonParser::parse($path)->toArray();
    }
}
