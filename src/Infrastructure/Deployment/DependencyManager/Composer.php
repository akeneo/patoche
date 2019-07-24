<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Infrastructure\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManager;
use Akeneo\Domain\Deployment\Dependency;
use Symfony\Component\Process\Process;
use Webmozart\Assert\Assert;

final class Composer implements DependencyManager
{
    private $pathToComposerExecutable;
    private $workingDirectory;

    public function __construct(string $pathToComposerExecutable, string $workingDirectory)
    {
        Assert::fileExists($pathToComposerExecutable, sprintf(
            'Could not find composer executable "%s".',
            $pathToComposerExecutable
        ));

        $this->pathToComposerExecutable = $pathToComposerExecutable;
        $this->workingDirectory = $workingDirectory;
    }

    public function require(Dependency $dependency): void
    {
        $process = new Process(
            [
                $this->pathToComposerExecutable,
                'require',
                '--no-update',
                (string) $dependency,
            ],
            $this->workingDirectory
        );

        $process->mustRun();
    }

    public function update(): void
    {
        $process = new Process(
            [
                $this->pathToComposerExecutable,
                'update',
            ],
            $this->workingDirectory
        );

        $process->mustRun();
    }
}
