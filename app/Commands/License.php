<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Template;

use function date;

class License extends Command
{
    protected ?string $template = '.templates/LICENSE';

    protected function handle(string $source, string $target): void
    {
        Template::replace($source, $target . '/LICENSE', [
            'year' => $this->year(),
        ]);
    }

    protected function year(): string
    {
        return date('Y');
    }
}
