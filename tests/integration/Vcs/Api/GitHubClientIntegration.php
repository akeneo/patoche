<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Integration\Vcs\Api;

use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Tags;
use Akeneo\Tests\Integration\TestCase;
use League\Flysystem\FilesystemInterface;
use PHPUnit\Framework\Assert;

class GitHubClientIntegration extends TestCase
{
    private const TESTED_ORGANIZATION = 'akeneo';
    private const TESTED_PROJECT = 'patoche';
    private const TESTED_BRANCH = '0.0';
    private const TESTED_WORKING_DIRECTORY = 'release-v0.0.1';

    /** @test */
    public function downloadGitHubRepositoryArchive(): void
    {
        $organization = new Organization(static::TESTED_ORGANIZATION);
        $project = new Project(static::TESTED_PROJECT);
        $branch = new Branch(static::TESTED_BRANCH);
        $workingDirectory = new WorkingDirectory(static::TESTED_WORKING_DIRECTORY);

        /** @var VcsApiClient $client */
        $client = $this->container()->get('Akeneo\Infrastructure\Vcs\Api\GitHubClient');
        $client->download($organization, $project, $branch, $workingDirectory);

        $this->assertContentIsDownloaded();
    }

    /** @test */
    public function getRepositoryTagList(): void
    {
        $organization = new Organization(static::TESTED_ORGANIZATION);
        $project = new Project(static::TESTED_PROJECT);

        /** @var VcsApiClient $client */
        $client = $this->container()->get('Akeneo\Infrastructure\Vcs\Api\GitHubClient');
        $tags = $client->listTags($organization, $project);

        Assert::assertInstanceOf(Tags::class, $tags);
        Assert::assertSame($tags->nextTagForBranch(new Branch('0.0'))->getVcsTag(), 'v0.0.2');
    }

    private function assertContentIsDownloaded(): void
    {
        /** @var FilesystemInterface $fileSystem */
        $fileSystem = $this->container()->get('League\Flysystem\Filesystem');
        $downloadedContents = $fileSystem->listContents(static::TESTED_WORKING_DIRECTORY);

        $downloadedRepositoryPath = '';
        foreach ($downloadedContents as $content) {
            if ('dir' === $content['type'] && 1 === preg_match('/^akeneo-patoche-.*/', $content['basename'])) {
                $downloadedRepositoryPath = $content['path'];
            }
        }

        $downloadedRepositoryReadme = $fileSystem->read($downloadedRepositoryPath . DIRECTORY_SEPARATOR . 'README.md');
        Assert::assertSame("# Testing Patoche with integration tests\n", $downloadedRepositoryReadme);
    }
}
