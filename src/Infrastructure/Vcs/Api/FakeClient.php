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
use League\Flysystem\FilesystemInterface;

final class FakeClient implements VcsApiClient
{
    public const DATA = [
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

    public function clone(string $organization, string $project, string $branch, string $destination): void
    {
        $this->filesystem->write(
            sprintf('%s/%s/README.md', $destination, $project),
            static::DATA[$organization][$project][$branch]
        );
    }
}
