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
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use Akeneo\Domain\Tagging\WorkingDirectory;
use PhpSpec\ObjectBehavior;

class CloneRepositorySpec extends ObjectBehavior
{
    private $repository;
    private $workingDirectory;

    function let()
    {
        $this->repository = new Repository(new Organization('akeneo'), new Project('onboarder'), new Branch('4.2'));
        $this->workingDirectory = new WorkingDirectory('release-4.2.0-5cc30e180c6fb');

        $this->beConstructedWith($this->repository, $this->workingDirectory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CloneRepository::class);
    }

    function it_returns_a_git_repository()
    {
        $this->getRepository()->shouldReturn($this->repository);
    }

    function it_returns_the_working_directory()
    {
        $this->getWorkingDirectory()->shouldReturn($this->workingDirectory);
    }
}
