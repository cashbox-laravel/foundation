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

namespace CashboxDev\Commands;

use CashboxDev\Services\Template;
use Cerbero\JsonParser\JsonParser;

class Readme extends Command
{
    protected ?string $template = '.templates/README.md';

    protected function handle(string $source, string $target): void
    {
        Template::replace($source, $target . '/README.md', $this->parse($target)->toArray());
    }

    protected function parse(string $path): JsonParser
    {
        return JsonParser::parse($path . '/composer.json')->pointers([
            '/name',
            '/description',
            '/extra/title',
        ]);
    }
}
