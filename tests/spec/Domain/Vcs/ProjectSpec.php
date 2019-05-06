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
}
