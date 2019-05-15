<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Application\Vcs;

use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Tags;

interface VcsApiClient
{
    public function download(
        Organization $organization,
        Project $project,
        Branch $branch,
        WorkingDirectory $destination
    ): void;

    /**
     * Will return either an empty array (if there are no tags at all) or a list of tags,
     * each tags being an array as follow:
     *
     * [
     *     'name' => 'v1.0.0',
     *     'zipball_url' => 'https://api.github.com/repos/akeneo/onboarder/zipball/v1.0.0',
     *     'tarball_url' => 'https://api.github.com/repos/akeneo/onboarder/tarball/v1.0.0',
     *     'commit' => [
     *         'sha' => 'a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29',
     *         'url' => 'https://api.github.com/repos/akeneo/onboarder/commits/a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29',
     *     ],
     *     'node_id' => 'MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBMQ==',
     * ]
     */
    public function listTags(Organization $organization, Project $project): Tags;
}
