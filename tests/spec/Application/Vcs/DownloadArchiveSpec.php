<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Vcs;

use Akeneo\Patoche\Application\Vcs\DownloadArchive;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class DownloadArchiveSpec extends ObjectBehavior
{
    private $repository;
    private $workingDirectory;

    function let()
    {
        $this->repository = new Repository(new Organization('akeneo'), new Project('onboarder'), new Branch('4.2'));
        $this->workingDirectory = new WorkingDirectory('release-v4.2.0');

        $this->beConstructedWith($this->repository, $this->workingDirectory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(DownloadArchive::class);
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
