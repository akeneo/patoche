<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManager;
use Akeneo\Domain\Deployment\Dependency;
use League\Flysystem\FilesystemInterface;

final class FakeComposer implements DependencyManager
{
    private $filesystem;
    private $relativePathToProject;

    public function __construct(FilesystemInterface $filesystem, string $relativePathToProject)
    {
        $this->filesystem = $filesystem;
        $this->relativePathToProject = $relativePathToProject;
    }

    public function require(Dependency $dependency): void
    {
        $composerJson = $this->filesystem->read($this->relativePathToProject . DIRECTORY_SEPARATOR . 'composer.json');
        $composerJsonAsArray = json_decode($composerJson, true);

        list($dependencyName, $dependencyVersion) = explode(':', (string) $dependency);

        $composerJsonAsArray['require'][$dependencyName] = $dependencyVersion;

        $this->filesystem->update(
            $this->relativePathToProject . DIRECTORY_SEPARATOR . 'composer.json',
            json_encode($composerJsonAsArray)
        );
    }

    public function update(): void
    {
        $composerJson = $this->filesystem->read($this->relativePathToProject . DIRECTORY_SEPARATOR . 'composer.json');

        $composerJsonAsArray = json_decode($composerJson, true);
        $composerLock = $composerJsonAsArray['require'];

        $this->filesystem->put(
            $this->relativePathToProject . DIRECTORY_SEPARATOR . 'composer.lock',
            json_encode($composerLock)
        );
    }
}
