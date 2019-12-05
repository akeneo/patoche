<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Infrastructure\Deployment\DependencyManager;

use Akeneo\Patoche\Application\Deployment\DependencyManager;
use Akeneo\Patoche\Domain\Deployment\Dependency;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

final class Composer implements DependencyManager
{
    private $pathToComposerExecutable;
    private $workingDirectory;
    private $timeout;

    public function __construct(string $pathToComposerExecutable, string $workingDirectory, int $timeout)
    {
        Assert::fileExists($pathToComposerExecutable, sprintf(
            'Could not find composer executable "%s".',
            $pathToComposerExecutable
        ));

        $this->pathToComposerExecutable = $pathToComposerExecutable;
        $this->workingDirectory = $workingDirectory;
        $this->timeout = $timeout;
    }

    public function require(Dependency $dependency): void
    {
        $process = new Process(
            [
                $this->pathToComposerExecutable,
                'require',
                '--no-update',
                '--no-interaction',
                '--no-scripts',
                '--prefer-dist',
                (string) $dependency,
            ],
            $this->workingDirectory
        );

        $process->setTimeout($this->timeout);

        $process->mustRun();
    }

    public function update(): void
    {
        $process = new Process(
            [
                $this->pathToComposerExecutable,
                'update',
                '--no-interaction',
                '--no-scripts',
                '--prefer-dist',
            ],
            $this->workingDirectory
        );

        $process->setTimeout($this->timeout);

        $process->mustRun();
    }
}
