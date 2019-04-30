<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
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
        $cloneRepository = new CloneRepository(
            new Repository(new Organization('akeneo'), new Project('onboarder'), new Branch('4.2')),
            new WorkingDirectory('release-4.2.0-5cc30e180c6fb')
        );

        $vcsApiClient->clone('akeneo', 'onboarder', '4.2', 'release-4.2.0-5cc30e180c6fb')->shouldBeCalled();

        $this->__invoke($cloneRepository);
    }
}
