<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Infrastructure\Deployment\DependencyManager;

use Akeneo\Application\Deployment\DependencyManager;
use Akeneo\Application\Deployment\DependencyManagerFactory;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Infrastructure\Deployment\DependencyManager\Composer;
use Akeneo\Infrastructure\Deployment\DependencyManager\ComposerFactory;
use PhpSpec\ObjectBehavior;

class ComposerFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('/usr/local/bin/composer', '/project_root/data/tests');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ComposerFactory::class);
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
        $dependencyManager->shouldReturnAnInstanceOf(Composer::class);
    }
}
