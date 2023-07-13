<?php

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
