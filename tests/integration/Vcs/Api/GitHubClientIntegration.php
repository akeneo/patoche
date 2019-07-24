<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Integration\Vcs\Api;

use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Tags;
use Akeneo\Infrastructure\Vcs\Api\GitHubClient;
use Akeneo\Tests\Integration\TestCase;
use League\Flysystem\Filesystem;
use PHPUnit\Framework\Assert;

class GitHubClientIntegration extends TestCase
{
    private const TESTED_WORKING_DIRECTORY = 'release-v0.0.2';
    private const TESTED_ORGANIZATION = 'akeneo';
    private const TESTED_PROJECT = 'patoche';
    private const TESTED_BRANCH = '0.0';

    /** @test */
    public function itDownloadsTheGitHubRepositoryArchiveForAProvidedBranch(): void
    {
        $organization = new Organization(static::TESTED_ORGANIZATION);
        $project = new Project(static::TESTED_PROJECT);
        $branch = new Branch(static::TESTED_BRANCH);
        $workingDirectory = new WorkingDirectory(static::TESTED_WORKING_DIRECTORY);

        $client = $this->container()->get(GitHubClient::class);
        $client->download($organization, $project, $branch, $workingDirectory);

        $this->assertContentIsDownloaded();
    }

    /** @test */
    public function itGetTheListOfTheRepositoryTags(): void
    {
        $organization = new Organization(static::TESTED_ORGANIZATION);
        $project = new Project(static::TESTED_PROJECT);

        $client = $this->container()->get(GitHubClient::class);
        $tags = $client->listTags($organization, $project);

        Assert::assertInstanceOf(Tags::class, $tags);
        Assert::assertSame($tags->nextTagForBranch(new Branch('0.0'))->getVcsTag(), 'v0.0.2');
    }

    /** @test */
    public function itGetsTheLastCommitOfAProvidedBranch(): void
    {
        $organization = new Organization(static::TESTED_ORGANIZATION);
        $project = new Project(static::TESTED_PROJECT);
        $branch = new Branch(static::TESTED_BRANCH);

        $client = $this->container()->get(GitHubClient::class);
        $commit = $client->getLastCommitForBranch($organization, $project, $branch);

        Assert::assertInstanceOf(Commit::class, $commit);
        Assert::assertSame((string) $commit, '7757b6a0ee80313fbbc42c2b7013fa523929c8c3');
    }

    private function assertContentIsDownloaded(): void
    {
        $fileSystem = $this->container()->get(Filesystem::class);
        $downloadedRepositoryReadme = $fileSystem->read(
            $this->pathToDownloadedPatoche() . DIRECTORY_SEPARATOR . 'README.md'
        );

        Assert::assertSame("# Testing Patoche with integration tests\n", $downloadedRepositoryReadme);
    }

    protected function pathToDownloadedPatoche(): string
    {
        $filesystem = $this->container()->get(Filesystem::class);
        $downloadedContents = $filesystem->listContents(static::TESTED_WORKING_DIRECTORY);

        $downloadedRepositoryPath = '';
        foreach ($downloadedContents as $content) {
            if ('dir' === $content['type'] && 1 === preg_match('/^akeneo-patoche-.*/', $content['basename'])) {
                $downloadedRepositoryPath = $content['path'];
            }
        }

        return $downloadedRepositoryPath;
    }
}
