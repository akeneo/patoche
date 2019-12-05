<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Patoche\Tests\Acceptance\Vcs\Api;

use Akeneo\Patoche\Application\Vcs\VcsApiClient;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Commit;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Tags;
use League\Flysystem\FilesystemInterface;

final class FakeClient implements VcsApiClient
{
    private const FAKE_DOWNLOADED_DATA = [
        'akeneo' => [
            'onboarder' => [
                '2.2' => 'Cloning akeneo/onboarder 2.2',
            ],
        ],
    ];

    private const FAKE_PREVIOUS_TAGS = [
        'akeneo' => [
            'onboarder' => [
                [
                    'name' => 'v2.2.0',
                    'zipball_url' => 'https://api.github.com/repos/akeneo/onboarder/zipball/v2.2.0',
                    'tarball_url' => 'https://api.github.com/repos/akeneo/onboarder/tarball/v2.2.0',
                    'commit' => [
                        'sha' => 'a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29',
                        'url' => 'https://api.github.com/repos/akeneo/onboarder/commits/a1d250fe6e7bd20e93c8c33ffd88ae11d72ceb29',
                    ],
                    'node_id' => 'MDM6UmVmMTE1MTIyOTU1OnYxLjAuMC1CRVRBMQ==',
                ],
            ],
        ],
    ];

    public const FAKE_BRANCHES = [
        'akeneo' => [
            'onboarder' => [
                '2.2' => [
                    'name' => '2.2',
                    'commit' => [
                        'sha' => 'eb39d8227797b960796fc1662b24da234c5cda13',
                    ],
                ],
            ],
            'pim-onboarder' => [
                '2.2' => [
                    'name' => '2.2',
                    'commit' => [
                        'sha' => '7757b6a0ee80313fbbc42c2b7013fa523929c8c3',
                    ],
                ],
            ],
            'pim-enterprise-cloud' => [
                '3.0' => [
                    'name' => '3.0',
                    'commit' => [
                        'sha' => '19d81de876d51d6b1ecb5cf39eed3d81f27a77cc',
                    ],
                ],
            ],
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
        return Tags::fromListTagsApiResponse(static::FAKE_PREVIOUS_TAGS[(string) $organization][(string) $project]);
    }

    public function getLastCommitForBranch(Organization $organization, Project $project, Branch $branch): Commit
    {
        return Commit::fromBranchesApiResponse(
            static::FAKE_BRANCHES[(string) $organization][(string) $project][(string) $branch]
        );
    }
}
