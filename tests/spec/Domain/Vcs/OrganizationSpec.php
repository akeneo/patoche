<?php

declare(strict_types=1);

/*
 * This file is part of Patoche.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace spec\Akeneo\Patoche\Domain\Vcs;

use Akeneo\Patoche\Domain\Vcs\Organization;
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

    function it_throws_an_exception_if_the_orgnanization_name_is_empty()
    {
        $this->beConstructedWith('');

        $this
            ->shouldThrow(new \InvalidArgumentException('You must specify a GitHub organization.'))
            ->duringInstantiation();
    }
}
