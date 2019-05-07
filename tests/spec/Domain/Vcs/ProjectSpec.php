<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs;

use Akeneo\Domain\Vcs\Project;
use PhpSpec\ObjectBehavior;

class ProjectSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('onboarder');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Project::class);
    }

    function it_returns_the_project_name()
    {
        $this->__toString()->shouldReturn('onboarder');
    }

    function it_throws_an_exception_if_project_name_is_empty()
    {
        $this->beConstructedWith('');
        $this
            ->shouldThrow(new \InvalidArgumentException('You must specify a project name.'))
            ->duringInstantiation();
    }
}
