<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Application\Deployment;

use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Commit;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;

interface DependencyManagerFactory
{
    public function create(
        WorkingDirectory $workingDirectory,
        Organization $organization,
        Project $project,
        Commit $commit
    ): DependencyManager;
}
