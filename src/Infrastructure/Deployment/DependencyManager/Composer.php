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

    public function __construct(string $pathToComposerBinary)
    {
//        Assert::fileExists($pathToComposerBinary, '');
        $this->pathToComposerBinary = $pathToComposerBinary;
    }

    public function require(Dependency $dependency): void
    {
        $process = new Process(
            [
                $this->pathToComposerBinary,
                'require',
                '--no-update',
                (string) $dependency
            ],
            '/srv/app/data/tests/release-v0.0.2/akeneo-patoche-7757b6a0ee80313fbbc42c2b7013fa523929c8c3'
        );

        $process->mustRun();
    }

    public function update(): void
    {
    }
}
