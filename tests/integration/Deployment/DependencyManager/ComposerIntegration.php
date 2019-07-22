<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Akeneo\Tests\Integration\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManager;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Deployment\Dependency;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Infrastructure\Vcs\Api\GitHubClient;
use Akeneo\Tests\Integration\TestCase;
use League\Flysystem\Filesystem;

final class ComposerIntegration extends TestCase
{
    private const TESTED_ORGANIZATION = 'akeneo';
    private const TESTED_PROJECT = 'patoche';
    private const TESTED_BRANCH = '0.0';

    public function setUp(): void
    {
        parent::setUp();

        $organization = new Organization(static::TESTED_ORGANIZATION);
        $project = new Project(static::TESTED_PROJECT);
        $branch = new Branch(static::TESTED_BRANCH);
        $workingDirectory = new WorkingDirectory(static::TESTED_WORKING_DIRECTORY);

        $client = $this->container()->get(GitHubClient::class);
        $client->download($organization, $project, $branch, $workingDirectory);
    }

    /** @test */
    public function itRequiresANewDependency(): void
    {
        $dependency = Dependency::fromBranchNameAndCommitReference(
            new Organization('symfony'),
            new Project('process'),
            new Branch('4.3'),
            Commit::fromBranchesApiResponse([
                'commit' => [
                    'sha' => '856d35814cf287480465bb7a6c413bb7f5f5e69c',
                ],
            ])
        );

        $composer = $this->container()->get(DependencyManager::class);
        $composer->require($dependency);

        $composerJsonAsArray = $this->getComposerJsonAsArray();
        $this->assertArrayHasKey(
            'symfony/process',
            $composerJsonAsArray['require']
        );
        $this->assertSame(
            '4.3.x-dev#856d35814cf287480465bb7a6c413bb7f5f5e69c@dev',
            $composerJsonAsArray['require']['symfony/process']
        );
    }

    /** @test */
    public function itUpdatesAnAlreadyPresentDependency(): void
    {
    }

    /** @test */
    public function itLocksDependencies(): void
    {
    }

    /** @test */
    public function itUpdatesLockedDependencies(): void
    {
    }

    /** @test */
    public function itAddsLockedDependencies(): void
    {
    }

    private function getComposerJsonAsArray(): array
    {
        $composerJsonContent = $this->container()->get(Filesystem::class)->read(
            $this->pathToDownloadedPatoche() . DIRECTORY_SEPARATOR . 'composer.json'
        );

        return json_decode($composerJsonContent, true);
    }
}
