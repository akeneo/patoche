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
use Akeneo\Domain\Vcs\Exception\InvalidBranchName;
use PhpSpec\ObjectBehavior;

class BranchSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('4.2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Branch::class);
    }

    function it_returns_the_branch_name()
    {
        $this->__toString()->shouldReturn('4.2');
    }

    function it_throws_an_exception_if_the_branch_name_is_not_a_version_number()
    {
        $this->beConstructedWith('foobar');
        $this->shouldThrow(InvalidBranchName::build('foobar'))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_branch_name_is_only_a_major_version_number()
    {
        $this->beConstructedWith('10');
        $this->shouldThrow(InvalidBranchName::build('10'))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_branch_name_is_a_patch_version_number()
    {
        $this->beConstructedWith('4.2.1');
        $this->shouldThrow(InvalidBranchName::build('4.2.1'))->duringInstantiation();
    }
}
