<?php

declare(strict_types=1);

namespace App\Commands;

use App\Services\Template;
use Cerbero\JsonParser\JsonParser;
use Symfony\Component\Console\Output\OutputInterface;

class Readme extends Command
{
    protected ?string $template = '.templates/README.md';

    protected function handle(OutputInterface $output): void
    {
        foreach ($this->projects() as $project) {
            $output->writeln('Processing: ' . $project);

            $this->process($this->template(), $project . '/README.md', $this->parse($project));
        }
    }

    protected function process(string $source, string $target, JsonParser $json): void
    {
        Template::replace($source, $target, $json->toArray());
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
