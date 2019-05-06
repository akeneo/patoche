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
use Akeneo\Domain\Vcs\Repository;
use Akeneo\Domain\Tagging\WorkingDirectory;
use PhpSpec\ObjectBehavior;

class CloneRepositorySpec extends ObjectBehavior
{
    function let(Repository $repository, WorkingDirectory $workingDirectory)
    {
        $this->beConstructedWith($repository, $workingDirectory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(CloneRepository::class);
    }

    function it_returns_a_git_repository($repository)
    {
        $this->getRepository()->shouldReturn($repository);
    }

    function it_returns_the_working_directory()
    {
        $this->getWorkingDirectory();
    }
}
