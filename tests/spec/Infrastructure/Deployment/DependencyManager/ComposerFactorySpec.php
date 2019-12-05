<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Infrastructure\Deployment\DependencyManager;

use Akeneo\Patoche\Application\Deployment\DependencyManagerFactory;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Commit;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Infrastructure\Deployment\DependencyManager\Composer;
use Akeneo\Patoche\Infrastructure\Deployment\DependencyManager\ComposerFactory;
use PhpSpec\ObjectBehavior;

class ComposerFactorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('/usr/local/bin/composer', '/project_root/data/tests', 300);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ComposerFactory::class);
        $this->shouldImplement(DependencyManagerFactory::class);
    }

    function it_instantiate_a_dependency_manager()
    {
        $workingDirectory = new WorkingDirectory('release-v0.0.2');
        $organization = new Organization('akeneo');
        $project = new Project('patoche');
        $commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => '7757b6a0ee80313fbbc42c2b7013fa523929c8c3',
            ],
        ]);

        $dependencyManager = $this->create($workingDirectory, $organization, $project, $commit);

        $dependencyManager->shouldBeLike(new Composer(
            '/usr/local/bin/composer',
            '/project_root/data/tests/release-v0.0.2/akeneo-patoche-7757b6a0ee80313fbbc42c2b7013fa523929c8c3',
            300
        ));
    }
}
