<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Tests\Acceptance\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManager;
use Akeneo\Application\Deployment\DependencyManagerFactory;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Tests\Acceptance\Deployment\DependencyManager\FakeComposer;
use Akeneo\Tests\Acceptance\Deployment\DependencyManager\FakeComposerFactory;
use League\Flysystem\FilesystemInterface;
use PhpSpec\ObjectBehavior;

class FakeComposerFactorySpec extends ObjectBehavior
{
    function let(FilesystemInterface $filesystem)
    {
        $this->beConstructedWith($filesystem);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(FakeComposerFactory::class);
        $this->shouldImplement(DependencyManagerFactory::class);
    }

    function it_instantiate_a_dependency_manager()
    {
        $workingDirectory = new WorkingDirectory('release-v0.0.2');
        $organization = new Organization('symfony');
        $project = new Project('process');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => '856d35814cf287480465bb7a6c413bb7f5f5e69c',
            ],
        ]);

        $dependencyManager = $this->create($workingDirectory, $organization, $project, $commit);

        $dependencyManager->shouldImplement(DependencyManager::class);
        $dependencyManager->shouldBeAnInstanceOf(FakeComposer::class);
    }
}
