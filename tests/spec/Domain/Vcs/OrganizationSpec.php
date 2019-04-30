<?php

declare(strict_types=1);

/*
 * This file is part of Onboarder Tagging.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Domain\Vcs;

use Akeneo\Domain\Vcs\Organization;
use PhpSpec\ObjectBehavior;

class OrganizationSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('akeneo');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Organization::class);
    }

    function it_returns_the_organization_name()
    {
        $this->__toString()->shouldReturn('akeneo');
    }
}
