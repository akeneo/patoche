<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Domain\Vcs;

use Akeneo\Patoche\Domain\Vcs\Branch;
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

    function it_throws_an_exception_if_branch_name_is_empty()
    {
        $this->beConstructedWith('');
        $this
            ->shouldThrow(new \InvalidArgumentException('You must specify a branch to work on.'))
            ->duringInstantiation();
    }

    function it_throws_an_exception_if_the_branch_name_is_not_a_version_number()
    {
        $this->beConstructedWith('foobar');
        $this->shouldThrow(new \InvalidArgumentException(
            'The branch name must be "master" or correspond to a minor version (i.e. "4.2", "10.0"), "foobar" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_branch_name_is_only_a_major_version_number()
    {
        $this->beConstructedWith('10');
        $this->shouldThrow(new \InvalidArgumentException(
            'The branch name must be "master" or correspond to a minor version (i.e. "4.2", "10.0"), "10" provided.'
        ))->duringInstantiation();
    }

    function it_throws_an_exception_if_the_branch_name_is_a_patch_version_number()
    {
        $this->beConstructedWith('4.2.1');
        $this->shouldThrow(new \InvalidArgumentException(
            'The branch name must be "master" or correspond to a minor version (i.e. "4.2", "10.0"), "4.2.1" provided.'
        ))->duringInstantiation();
    }

    function it_accepts_master_as_a_branch_name()
    {
        $this->beConstructedWith('master');
        $this->__toString()->shouldReturn('master');
    }
}
