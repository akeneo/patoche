<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Application\Onboarder;

use Akeneo\Patoche\Application\Onboarder\Exception\BranchNotMapped;
use Akeneo\Patoche\Application\Onboarder\MappedBranches;
use Akeneo\Patoche\Domain\Vcs\Branch;
use PhpSpec\ObjectBehavior;

class MappedBranchesSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedThrough('fromRawMapping', [['1.2' => '2.3', '2.0' => '3.0']]);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(MappedBranches::class);
    }

    function it_returns_the_pim_enterprise_cloud_branch_mapped_to_a_given_onboarder_branch()
    {
        $this->getPecMappedBranched(new Branch('1.2'))->shouldBeLike(new Branch('2.3'));
    }

    function it_throws_an_exception_if_the_project_branch_is_not_mapped()
    {
        $branch = new Branch('1.0');

        $exception = new BranchNotMapped($branch);
        $this->shouldThrow($exception)->during('getPecMappedBranched', [$branch]);
    }
}
