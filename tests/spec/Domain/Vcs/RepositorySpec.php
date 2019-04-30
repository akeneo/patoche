<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs;

use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use Akeneo\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class RepositorySpec extends ObjectBehavior
{
    private $organization;
    private $project;
    private $branch;

    function let()
    {
        $this->organization = new Organization('akeneo');
        $this->project = new Project('onboarder');
        $this->branch = new Branch('4.2');

        $this->beConstructedWith($this->organization, $this->project, $this->branch);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_returns_the_organization_of_the_repository()
    {
        $this->getOrganization()->shouldReturn($this->organization);
    }

    function it_returns_the_project_of_the_repository()
    {
        $this->getProject()->shouldReturn($this->project);
    }

    function it_returns_the_branch_of_the_repository()
    {
        $this->getBranch()->shouldReturn($this->branch);
    }
}
