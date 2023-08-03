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

namespace CashboxDev\Services;

use DragonCode\Support\Facades\Helpers\Str;

use function file_get_contents;
use function file_put_contents;

class Template
{
    public static function replace(string $source, string $target, array $replaces): void
    {
        static::store($target, static::format(static::load($source), $replaces));
    }

    protected static function load(string $path): string
    {
        return file_get_contents($path);
    }

    protected static function format(string $template, array $replaces): string
    {
        return Str::replaceFormat($template, $replaces, '{{%s}}');
    }

    protected static function store(string $path, string $content): void
    {
        file_put_contents($path, $content . PHP_EOL);
    }
}
