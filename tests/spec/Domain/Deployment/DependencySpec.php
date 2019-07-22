<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Deployment;

use Akeneo\Domain\Deployment\Dependency;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Commit;
use Akeneo\Domain\Vcs\Organization;
use Akeneo\Domain\Vcs\Project;
use PhpSpec\ObjectBehavior;

class DependencySpec extends ObjectBehavior
{
    private $organization;
    private $project;
    private $branch;
    private $commit;

    function let()
    {
        $this->organization = new Organization('akeneo');
        $this->project = new Project('pim-onboarder');
        $this->branch = new Branch('master');
        $this->commit = Commit::fromBranchesApiResponse([
            'commit' => [
                'sha' => 'c0b506049ba79bc41ca1bb2be62a8c8b7b329954',
            ],
        ]);

        $this->beConstructedThrough('fromBranchNameAndCommitReference', [
            $this->organization,
            $this->project,
            $this->branch,
            $this->commit,
        ]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Dependency::class);
    }

    function it_returns_the_dependency_for_the_master_branch()
    {
        $this->__toString()->shouldReturn(
            'akeneo/pim-onboarder:dev-master#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev'
        );
    }

    function it_returns_the_dependency_for_a_minor_version_branch()
    {
        $this->branch = new Branch('2.2');

        $this->beConstructedThrough('fromBranchNameAndCommitReference', [
            $this->organization,
            $this->project,
            $this->branch,
            $this->commit,
        ]);

        $this->__toString()->shouldReturn(
            'akeneo/pim-onboarder:2.2.x-dev#c0b506049ba79bc41ca1bb2be62a8c8b7b329954@dev'
        );
    }
}
