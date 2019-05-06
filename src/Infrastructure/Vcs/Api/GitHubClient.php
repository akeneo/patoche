<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Infrastructure\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Tagging\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;

final class GitHubClient implements VcsApiClient
{
    public function clone(
        Organization $organization,
        Project $project,
        Branch $branch,
        WorkingDirectory $destination
    ): void {
        throw new \LogicException('Not implemented step!');
    }
}
