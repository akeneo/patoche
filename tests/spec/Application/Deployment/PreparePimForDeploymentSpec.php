<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Deployment;

use Akeneo\Application\Deployment\PreparePimForDeployment;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class PreparePimForDeploymentSpec extends ObjectBehavior
{
    private $repository;
    private $workingDirectory;

    function let()
    {
        $this->repository = new Repository(
            new Organization('akeneo'),
            new Project('pim-enterprise-cloud'),
            new Branch('3.0')
        );
        $this->workingDirectory = new WorkingDirectory('release-v4.2.0');

        $this->beConstructedWith($this->repository, $this->workingDirectory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PreparePimForDeployment::class);
    }

    function it_returns_the_repository()
    {
        $this->getRepository()->shouldReturn($this->repository);
    }

    function it_returns_the_working_directory()
    {
        $this->getWorkingDirectory()->shouldReturn($this->workingDirectory);
    }
}
