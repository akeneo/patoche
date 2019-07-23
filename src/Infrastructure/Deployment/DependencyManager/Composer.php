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

final class Composer implements DependencyManager
{
    private $pathToComposerBinary;
    private $workingDirectory;

    public function __construct(string $pathToComposerBinary, string $workingDirectory)
    {
//        Assert::fileExists($pathToComposerBinary, '');
        $this->pathToComposerBinary = $pathToComposerBinary;
        $this->workingDirectory = $workingDirectory;
    }

    public function require(Dependency $dependency): void
    {
        $process = new Process(
            [
                $this->pathToComposerBinary,
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
    }
}
