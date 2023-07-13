<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Template;
use Symfony\Component\Console\Output\OutputInterface;

use function date;

class License extends Command
{
    protected ?string $template = '.templates/LICENSE';

    protected function handle(OutputInterface $output): void
    {
        foreach ($this->projects() as $project) {
            $this->process($this->template(), $project . '/LICENSE');
        }
    }

    protected function process(string $source, string $target): void
    {
        Template::replace($source, $target, [
            'year' => $this->year(),
        ]);
    }

    protected function year(): string
    {
        return date('Y');
    }
}
