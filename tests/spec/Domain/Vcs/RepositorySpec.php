<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs;

use Akeneo\Domain\Vcs\Repository;
use PhpSpec\ObjectBehavior;

class RepositorySpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('akeneo', 'onboarder', '4.2');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Repository::class);
    }

    function it_returns_the_name_of_the_repository()
    {
        $this->getName()->shouldReturn('akeneo/onboarder');
    }

    function it_returns_the_branch_of_the_repository()
    {
        $this->getBranch()->shouldReturn('4.2');
    }
}
