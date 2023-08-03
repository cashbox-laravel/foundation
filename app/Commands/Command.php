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

use DragonCode\Support\Facades\Filesystem\Directory;
use DragonCode\Support\Facades\Instances\Instance;
use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class Command extends BaseCommand
{
    protected ?string $template = null;

    abstract protected function handle(string $source, string $target): void;

    protected function configure(): void
    {
        $this->setName($this->commandName());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->prepare();

        foreach ($this->projects() as $project) {
            $output->writeln('Processing: ' . $project);

            $this->handle($this->template(), $project);
        }

        $this->finish();

        return static::SUCCESS;
    }

    protected function prepare(): void {}

    protected function finish(): void {}

    protected function projects(): array
    {
        return Directory::allPaths($this->basePath('src'));
    }

    protected function template(): string
    {
        return $this->basePath($this->template);
    }

    protected function basePath(?string $path = null): string
    {
        return __DIR__ . '/../../' . $path;
    }

    protected function commandName(): string
    {
        return Instance::basename($this);
    }
}
