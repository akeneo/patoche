<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Application\Vcs;

use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Commit;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Tags;

interface VcsApiClient
{
    public function download(
        Organization $organization,
        Project $project,
        Branch $branch,
        WorkingDirectory $destination
    ): void;

    public function listTags(Organization $organization, Project $project): Tags;

    public function getLastCommitForBranch(Organization $organization, Project $project, Branch $branch): Commit;
}
