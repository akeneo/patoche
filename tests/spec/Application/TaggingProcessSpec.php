<?php

declare(strict_types=1);

/*
 * This file is part of Patrick Tag.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Application;

use Akeneo\Application\TaggingProcess;
use PhpSpec\ObjectBehavior;

class TaggingProcessSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('1.0', '1.0.0', 'akeneo');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TaggingProcess::class);
    }

    function it_has_a_branch_to_work_on()
    {
        $this->getBranch()->shouldReturn('1.0');
    }

    function it_returns_a_tag_to_create()
    {
        $this->getTag()->shouldReturn('1.0.0');
    }

    function it_returns_the_organization_to_tag_on()
    {
        $this->getOrganization()->shouldReturn('akeneo');
    }

    function it_returns_a_working_directory()
    {
        $workingDirectory = $this->getWorkingDirectory();
        $workingDirectory->shouldBeString();
        $workingDirectory->shouldMatch('/^release-[0-9].[0-9].[0-9]$/');
    }

    function it_starts_with_an_empty_list_of_place()
    {
        $this->getPlaces()->shouldReturn([]);
    }

    function it_can_change_places()
    {
        $this->setPlaces(['next_place_1', 'next_place_2']);

        $this->getPlaces()->shouldReturn(['next_place_1', 'next_place_2']);
    }
}
