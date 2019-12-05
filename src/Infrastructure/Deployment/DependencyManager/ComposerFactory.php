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
use Akeneo\Patoche\Application\Deployment\DependencyManagerFactory;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Commit;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;

final class ComposerFactory implements DependencyManagerFactory
{
    private $pathToComposerExecutable;
    private $dataRootDirectory;
    private $timeout;

    public function __construct(
        string $pathToComposerExecutable,
        string $dataRootDirectory,
        int $timeout
    ) {
        $this->pathToComposerExecutable = $pathToComposerExecutable;
        $this->dataRootDirectory = $dataRootDirectory;
        $this->timeout = $timeout;
    }

    public function create(
        WorkingDirectory $workingDirectory,
        Organization $organization,
        Project $project,
        Commit $commit
    ): DependencyManager {
        $projectDirectory = sprintf(
            '%s-%s-%s',
            $organization,
            $project,
            $commit
        );

        return new Composer(
            $this->pathToComposerExecutable,
            sprintf(
                '%s/%s',
                $this->dataRootDirectory,
                $workingDirectory . DIRECTORY_SEPARATOR . $projectDirectory
            ),
            $this->timeout
        );
    }
}
