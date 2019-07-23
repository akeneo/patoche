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
use Akeneo\Application\Deployment\DependencyManagerFactory;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use League\Flysystem\FilesystemInterface;

final class FakeComposerFactory implements DependencyManagerFactory
{
    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function create(
        WorkingDirectory $workingDirectory,
        Organization $organization,
        Project $project,
        Commit $commit
    ): DependencyManager {
        return new FakeComposer($this->filesystem);
    }
}
