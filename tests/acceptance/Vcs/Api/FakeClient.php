<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Acceptance\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use League\Flysystem\FilesystemInterface;

final class FakeClient implements VcsApiClient
{
    private const DATA = [
        'akeneo' => [
            'onboarder' => [
                '4.2' => 'Cloning akeneo/onboarder 4.2',
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
            static::DATA[(string) $organization][(string) $project][(string) $branch]
        );
    }
}
