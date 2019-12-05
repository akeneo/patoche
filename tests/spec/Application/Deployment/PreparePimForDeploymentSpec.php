<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Deployment;

use Akeneo\Patoche\Application\Deployment\PreparePimForDeployment;
use Akeneo\Patoche\Domain\Common\WorkingDirectory;
use Akeneo\Patoche\Domain\Vcs\Branch;
use Akeneo\Patoche\Domain\Vcs\Organization;
use Akeneo\Patoche\Domain\Vcs\Project;
use Akeneo\Patoche\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class PreparePimForDeploymentSpec extends ObjectBehavior
{
    private $dependencyRepository;
    private $pecBranch;
    private $workingDirectory;

    function let()
    {
        $this->dependencyRepository = new Repository(
            new Organization('akeneo'),
            new Project('pim-onboarder'),
            new Branch('2.2')
        );
        $this->pecBranch = new Branch('3.0');
        $this->workingDirectory = new WorkingDirectory('release-v2.2.0');

        $this->beConstructedWith(
            $this->dependencyRepository,
            $this->pecBranch,
            $this->workingDirectory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(PreparePimForDeployment::class);
    }

    function it_returns_the_dependencyRepository()
    {
        $this->getRepository()->shouldReturn($this->dependencyRepository);
    }

    function it_returns_the_pim_enterprise_cloud_branch()
    {
        $this->getPecBranch()->shouldReturn($this->pecBranch);
    }

    function it_returns_the_working_directory()
    {
        $this->getWorkingDirectory()->shouldReturn($this->workingDirectory);
    }
}
