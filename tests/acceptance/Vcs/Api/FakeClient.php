<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Tags;
use League\Flysystem\FilesystemInterface;

final class FakeClient implements VcsApiClient
{
    private const FAKE_DOWNLOADED_DATA = [
        'akeneo' => [
            'onboarder' => [
                '4.2' => 'Cloning akeneo/onboarder 4.2',
            ],
        ],
    ];

    private const FAKE_PREVIOUS_TAGS = [
        [
            'name' => 'v4.2.0',
            'zipball_url' => 'https://api.github.com/repos/akeneo/onboarder/zipball/v4.2.0',
            'tarball_url' => 'https://api.github.com/repos/akeneo/onboarder/tarball/v4.2.0',
            'commit' => [
                'sha' => 'a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29',
                'url' => 'https://api.github.com/repos/akeneo/onboarder/commits/a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29',
            ],
            'node_id' => 'MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBMQ==',
        ],
    ];

    private const FAKE_BRANCH = [
        'name' => '4.2',
        'commit' => [
            'sha' => 'eb39d8227797b960796fc1662b24da234c5cda13',
        ],
    ];

    private $filesystem;

    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    public function download(
        Organization $organization,
        Project $project,
        Branch $branch,
        WorkingDirectory $destination
    ): void {
        $this->filesystem->write(
            sprintf('%s/%s/README.md', $destination, $project),
            static::FAKE_DOWNLOADED_DATA[(string) $organization][(string) $project][(string) $branch]
        );
    }

    public function listTags(Organization $organization, Project $project): Tags
    {
        return Tags::fromListTagsApiResponse(static::FAKE_PREVIOUS_TAGS);
    }

    public function getLastCommitForBranch(Organization $organization, Project $project, Branch $branch): Commit
    {
        return Commit::fromBranchesApiResponse(static::FAKE_BRANCH);
    }
}
