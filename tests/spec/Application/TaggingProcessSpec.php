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
use Akeneo\Domain\Common\Tag;
use Akeneo\Domain\Common\WorkingDirectory;
use Akeneo\Domain\Vcs\Branch;
use Akeneo\Domain\Vcs\Organization;
use PhpSpec\ObjectBehavior;

class TaggingProcessSpec extends ObjectBehavior
{
    private $branch;
    private $tag;
    private $organization;

    function let()
    {
        $this->branch = new Branch('1.0');
        $this->tag = new Tag('1.0.0');
        $this->organization = new Organization('akeneo');

        $this->beConstructedWith($this->branch, $this->tag, $this->organization);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(TaggingProcess::class);
    }

    function it_has_a_branch_to_work_on()
    {
        $this->getBranch()->shouldReturn($this->branch);
    }

    function it_returns_a_tag_to_create()
    {
        $this->getTag()->shouldReturn($this->tag);
    }

    function it_returns_the_organization_to_tag_on()
    {
        $this->getOrganization()->shouldReturn($this->organization);
    }

    function it_returns_a_working_directory()
    {
        $workingDirectory = $this->getWorkingDirectory();
        $workingDirectory->shouldBeAnInstanceOf(WorkingDirectory::class);
        $workingDirectory->__toString()->shouldReturn('release-v1.0.0');
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