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
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Tags;
use Github\Api\ApiInterface;
use Github\Api\Repo;
use Github\Client;
use League\Flysystem\FilesystemInterface;

final class GitHubClient implements VcsApiClient
{
    private $client;
    private $filesystem;
    private $filesystemDirectory;

    public function __construct(Client $client, FilesystemInterface $filesystem, string $filesystemDirectory)
    {
        $this->client = $client;
        $this->filesystem = $filesystem;
        $this->filesystemDirectory = $filesystemDirectory;
    }

    public function download(
        Organization $organization,
        Project $project,
        Branch $branch,
        WorkingDirectory $workingDirectory
    ): void {
        $archiveRelativePath = $workingDirectory . DIRECTORY_SEPARATOR . $project . '.zip';

        $archive = $this->repositoryApi()->contents()->archive(
            (string) $organization,
            (string) $project,
            'zipball',
            (string) $branch
        );

        $this->filesystem->write($archiveRelativePath, $archive);

        $archive = new \ZipArchive();
        $archive->open($this->filesystemDirectory . DIRECTORY_SEPARATOR . $archiveRelativePath, \ZipArchive::CREATE);
        $archive->extractTo($this->filesystemDirectory . DIRECTORY_SEPARATOR . $workingDirectory);
        $archive->close();
    }

    public function listTags(Organization $organization, Project $project): Tags
    {
        $apiResponse = $this->repositoryApi()->tags((string) $organization, (string) $project);

        return Tags::fromListTagsApiResponse($apiResponse);
    }

    /**
     * @return Repo
     */
    private function repositoryApi(): ApiInterface
    {
        return $this->client->api('repo');
    }
}
