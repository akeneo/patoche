<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Vcs;

use Akeneo\Application\Vcs\CloneRepository;
use Akeneo\Application\Vcs\CloneRepositoryHandler;
use Akeneo\Application\Vcs\VcsApiClient;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Akeneo\Domain\Tagging\WorkingDirectory;
use PhpSpec\ObjectBehavior;

class CloneRepositoryHandlerSpec extends ObjectBehavior
{
    function let(VcsApiClient $vcsApiClient)
    {
        $this->beConstructedWith($vcsApiClient);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CloneRepositoryHandler::class);
    }

    function it_clones_a_git_repository($vcsApiClient)
    {
        $organization = new Organization('akeneo');
        $project = new Project('onboarder');
        $branch = new Branch('4.2');
        $workingDirectory = new WorkingDirectory('release-4.2.0');

        $cloneRepository = new CloneRepository(
            new Repository($organization, $project, $branch),
            $workingDirectory
        );

        $vcsApiClient->clone($organization, $project, $branch, $workingDirectory)->shouldBeCalled();

        $this->__invoke($cloneRepository);
    }
}
