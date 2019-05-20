<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application\Onboarder;

use Akeneo\Application\Onboarder\Exception\BranchNotMapped;
use Akeneo\Application\Onboarder\MappedBranches;
use Akeneo\Application\Onboarder\OnboarderRelease;
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use PhpSpec\ObjectBehavior;

class OnboarderReleaseSpec extends ObjectBehavior
{
    private $branch;
    private $tag;
    private $organization;

    function let(): void
    {
        $this->branch = new Branch('2.0');
        $this->tag = Tag::fromGenericTag('2.0.0');
        $this->organization = new Organization('akeneo');

        $this->beConstructedWith(
            $this->branch,
            $this->tag,
            $this->organization,
            MappedBranches::fromRawMapping([
                '1.2' => '2.3',
                '2.0' => '3.0',
            ])
        );
    }

    function it_is_initializable(): void
    {
        $this->shouldHaveType(OnboarderRelease::class);
    }

    function it_has_a_branch_to_work_on(): void
    {
        $this->getBranch()->shouldReturn($this->branch);
    }

    function it_knows_the_corresponding_pim_enterprise_2x_cloud_branch(): void
    {
        $this->beConstructedWith(
            new Branch('1.2'),
            Tag::fromGenericTag('1.2.0'),
            new Organization('akeneo'),
            MappedBranches::fromRawMapping([
                '1.2' => '2.3',
                '2.0' => '3.0',
            ])
        );

        $pecBranch = $this->getBranchForProject(new Project('pim-enterprise-cloud'));
        $pecBranch->shouldBeAnInstanceOf(Branch::class);
        $pecBranch->__toString()->shouldReturn('2.3');
    }

    function it_knows_the_corresponding_pim_enterprise_3x_cloud_branch(): void
    {
        $pecBranch = $this->getBranchForProject(new Project('pim-enterprise-cloud'));
        $pecBranch->shouldBeAnInstanceOf(Branch::class);
        $pecBranch->__toString()->shouldReturn('3.0');
    }

    function it_returns_the_onboarder_projects_branch(): void
    {
        $pecBranch = $this->getBranchForProject(new Project('onboarder'));
        $pecBranch->shouldBeAnInstanceOf(Branch::class);
        $pecBranch->__toString()->shouldReturn('2.0');
    }

    function it_returns_a_tag_to_create(): void
    {
        $this->getTag()->shouldReturn($this->tag);
    }

    function it_returns_the_organization_to_tag_on(): void
    {
        $this->getOrganization()->shouldReturn($this->organization);
    }

    function it_returns_a_working_directory(): void
    {
        $workingDirectory = $this->getWorkingDirectory();
        $workingDirectory->shouldBeAnInstanceOf(WorkingDirectory::class);
        $workingDirectory->__toString()->shouldReturn('release-v2.0.0');
    }

    function it_starts_with_an_empty_list_of_place(): void
    {
        $this->getPlaces()->shouldReturn([]);
    }

    function it_can_change_places(): void
    {
        $this->setPlaces(['next_place_1', 'next_place_2']);

        $this->getPlaces()->shouldReturn(['next_place_1', 'next_place_2']);
    }

    function it_throws_an_exception_if_the_project_branch_is_not_mapped(): void
    {
        $branch = new Branch('1.0');
        $this->beConstructedWith(
            $branch,
            Tag::fromGenericTag('1.0.0'),
            new Organization('akeneo'),
            MappedBranches::fromRawMapping([
                '1.2' => '2.3',
                '2.0' => '3.0',
            ])
        );

        $exception = new BranchNotMapped($branch);
        $this->shouldThrow($exception)->during('getBranchForProject', [new Project('pim-enterprise-cloud')]);
    }
}
